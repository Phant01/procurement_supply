<?php
class ItemModel extends Model {
    protected string $table      = 'items';
    protected string $primaryKey = 'item_id';

    public function getWithCategory(): array {
        return $this->db->fetchAll(
            "SELECT i.*, ic.category_name, ic.item_type
             FROM items i
             JOIN item_categories ic ON ic.category_id = i.category_id
             WHERE i.is_active = 1
             ORDER BY i.item_name"
        );
    }

    public function search(string $q): array {
        return $this->db->fetchAll(
            "SELECT i.*, ic.item_type FROM items i
             JOIN item_categories ic ON ic.category_id = i.category_id
             WHERE i.is_active = 1
             AND (i.item_name LIKE ? OR i.item_code LIKE ?)
             ORDER BY i.item_name LIMIT 30",
            ["%$q%", "%$q%"]
        );
    }

    public function getLowStock(): array {
        return $this->db->fetchAll(
            "SELECT v.*, i.reorder_point FROM v_current_stock v
             JOIN items i ON i.item_id = v.item_id
             WHERE v.balance_qty <= i.reorder_point
             ORDER BY v.item_name"
        );
    }
}
