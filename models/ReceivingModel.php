<?php
class ReceivingModel extends Model {
    protected string $table      = 'po_receipts';
    protected string $primaryKey = 'receipt_id';

    public function getList(): array {
        return $this->db->fetchAll(
            "SELECT pr.*, po.po_number, s.supplier_name
             FROM po_receipts pr
             JOIN purchase_orders po ON po.po_id = pr.po_id
             JOIN suppliers s ON s.supplier_id = po.supplier_id
             ORDER BY pr.receipt_date DESC, pr.receipt_id DESC"
        );
    }

    public function getWithDetails(int $id): array|false {
        return $this->db->fetchOne(
            "SELECT pr.*, po.po_number, po.po_date,
                    s.supplier_name, o.office_name
             FROM po_receipts pr
             JOIN purchase_orders po ON po.po_id   = pr.po_id
             JOIN suppliers s        ON s.supplier_id = po.supplier_id
             JOIN offices   o        ON o.office_id   = po.office_id
             WHERE pr.receipt_id = ?", [$id]
        );
    }

    public function getItems(int $receiptId): array {
        return $this->db->fetchAll(
            "SELECT pri.*, i.item_name, i.unit_of_measure,
                    pi.qty_ordered, pi.qty_received AS total_received
             FROM po_receipt_items pri
             JOIN po_items pi ON pi.po_item_id = pri.po_item_id
             JOIN items i    ON i.item_id      = pi.item_id
             WHERE pri.receipt_id = ?", [$receiptId]
        );
    }

    public function createReceipt(array $header, array $lines): int {
    $db = $this->db;
    $db->beginTransaction();
    try {
        // Insert receipt header
        $cols = implode(', ', array_keys($header));
        $phs  = implode(', ', array_fill(0, count($header), '?'));
        $db->execute(
            "INSERT INTO po_receipts ($cols) VALUES ($phs)",
            array_values($header)
        );
        $receiptId = (int) $db->lastInsertId();

        foreach ($lines as $line) {
            // Resolve item_id from po_item
            $poItem = $db->fetchOne(
                "SELECT pi.item_id FROM po_items pi WHERE pi.po_item_id = ?",
                [$line['po_item_id']]
            );

            if (!$poItem) continue;
            $itemId = (int) $poItem['item_id'];

            // ── Safety net: create stock card if missing ──────────────────
            $sc = $db->fetchOne(
                "SELECT stock_card_id FROM stock_cards WHERE item_id = ?",
                [$itemId]
            );
            if (!$sc) {
                $db->execute(
                    "INSERT INTO stock_cards (item_id, balance_qty) VALUES (?, 0)",
                    [$itemId]
                );
            }
            // ─────────────────────────────────────────────────────────────

            // Insert receipt line — trigger handles stock card update
            $line['receipt_id'] = $receiptId;
            $c = implode(', ', array_keys($line));
            $p = implode(', ', array_fill(0, count($line), '?'));
            $db->execute(
                "INSERT INTO po_receipt_items ($c) VALUES ($p)",
                array_values($line)
            );
        }

        // Refresh PO status
        $po = $db->fetchOne(
            "SELECT po_id FROM po_receipts WHERE receipt_id = ?",
            [$receiptId]
        );
        $this->refreshPoStatus((int) $po['po_id']);

        $db->commit();
        return $receiptId;

    } catch (\Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

    private function refreshPoStatus(int $poId): void {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS total,
                    SUM(CASE WHEN qty_received >= qty_ordered THEN 1 ELSE 0 END) AS done
             FROM po_items WHERE po_id = ?", [$poId]
        );
        $status = ((int)$row['done'] === (int)$row['total'])
            ? 'fully_received' : 'partially_received';
        $this->db->execute(
            "UPDATE purchase_orders SET status = ? WHERE po_id = ?",
            [$status, $poId]
        );
    }
}
