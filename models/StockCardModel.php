<?php
class StockCardModel extends Model {
    protected string $table      = 'stock_cards';
    protected string $primaryKey = 'stock_card_id';

    public function getLedger(int $itemId, string $from = '', string $to = ''): array {
    $params = [$itemId];
    $where  = '';

    if ($from && $to) {
        $where    = " AND txn_date BETWEEN ? AND ?";
        $params[] = $from;
        $params[] = $to;
    }

    return $this->db->fetchAll(
        "SELECT * FROM v_stock_card_ledger
         WHERE item_id = ? $where
         ORDER BY txn_date ASC, entry_id ASC",
        $params
    );
    }

    public function getAllBalances(): array {
        return $this->db->fetchAll("SELECT * FROM v_current_stock ORDER BY item_name");
    }

    public function addAdjustment(int $itemId, float $qty, string $type, string $remarks, string $date): bool {
        $sc = $this->findOneWhere('item_id = ?', [$itemId]);
        if (!$sc) return false;

        $qtyIn  = $type === 'add'    ? $qty : 0;
        $qtyOut = $type === 'deduct' ? $qty : 0;
        $newBal = (float)$sc['balance_qty'] + $qtyIn - $qtyOut;

        if ($newBal < 0) throw new \Exception('Adjustment would result in negative balance.');

        $this->db->execute(
            "INSERT INTO stock_card_entries
             (stock_card_id, txn_date, ref_type, ref_id, ref_number,
              qty_in, qty_out, unit_cost, balance, remarks)
             VALUES (?, ?, 'ADJUSTMENT', 0, 'ADJUSTMENT', ?, ?, 0, ?, ?)",
            [$sc['stock_card_id'], $date, $qtyIn, $qtyOut, $newBal, $remarks]
        );
        $this->db->execute(
            "UPDATE stock_cards SET balance_qty = ? WHERE stock_card_id = ?",
            [$newBal, $sc['stock_card_id']]
        );
        return true;
    }
}
