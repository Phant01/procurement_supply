<?php
class RisModel extends Model {
    protected string $table      = 'ris';
    protected string $primaryKey = 'ris_id';

    public function getList(): array {
        return $this->db->fetchAll(
            "SELECT r.*, o.office_name FROM ris r
             JOIN offices o ON o.office_id = r.office_id
             ORDER BY r.ris_date DESC, r.ris_id DESC"
        );
    }

    public function getWithDetails(int $id): array|false {
        return $this->db->fetchOne(
            "SELECT r.*, o.office_name, o.department
             FROM ris r JOIN offices o ON o.office_id = r.office_id
             WHERE r.ris_id = ?", [$id]
        );
    }

    public function getItems(int $risId): array {
        return $this->db->fetchAll(
            "SELECT ri.*, i.item_name, i.unit_of_measure, ic.item_type,
                    sc.balance_qty
             FROM ris_items ri
             JOIN items i ON i.item_id = ri.item_id
             JOIN item_categories ic ON ic.category_id = i.category_id
             JOIN stock_cards sc ON sc.item_id = i.item_id
             WHERE ri.ris_id = ?", [$risId]
        );
    }

    public function issueRis(int $risId, array $lines, array $signatories): bool {
        $db = $this->db;
        $db->beginTransaction();
        try {
            foreach ($lines as $line) {
                $db->execute(
                    "UPDATE ris_items SET qty_issued = ?, unit_cost = ?
                     WHERE ris_item_id = ?",
                    [$line['qty_issued'], $line['unit_cost'], $line['ris_item_id']]
                );
            }
            $db->execute(
                "UPDATE ris SET status = 'issued', issued_by = ?,
                  received_by = ? WHERE ris_id = ?",
                [$signatories['issued_by'], $signatories['received_by'], $risId]
            );
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
