<?php
class PurchaseOrderModel extends Model {
    protected string $table      = 'purchase_orders';
    protected string $primaryKey = 'po_id';

    public function getList(int $page = 1, string $status = ''): array {
        $where  = $status ? "po.status = ?" : "1=1";
        $params = $status ? [$status]        : [];
        $sql = "SELECT po.*, s.supplier_name, o.office_name
                FROM purchase_orders po
                JOIN suppliers s ON s.supplier_id = po.supplier_id
                JOIN offices   o ON o.office_id   = po.office_id
                WHERE $where
                ORDER BY po.po_date DESC, po.po_id DESC";
        return $this->db->fetchAll($sql, $params);
    }

    public function getWithDetails(int $id): array|false {
        return $this->db->fetchOne(
            "SELECT po.*, s.supplier_name, s.address AS supplier_address,
                    s.tin_no, o.office_name
             FROM purchase_orders po
             JOIN suppliers s ON s.supplier_id = po.supplier_id
             JOIN offices   o ON o.office_id   = po.office_id
             WHERE po.po_id = ?", [$id]
        );
    }

    public function getItems(int $poId): array {
        return $this->db->fetchAll(
            "SELECT pi.*, i.item_name, i.description, ic.item_type
             FROM po_items pi
             JOIN items i ON i.item_id = pi.item_id
             JOIN item_categories ic ON ic.category_id = i.category_id
             WHERE pi.po_id = ?
             ORDER BY pi.po_item_id", [$poId]
        );
    }

    public function updateStatus(int $id, string $status): bool {
        return $this->update($id, ['status' => $status]);
    }

    public function getTotalAmount(int $poId): float {
        $row = $this->db->fetchOne(
            "SELECT SUM(qty_ordered * unit_price) AS total FROM po_items WHERE po_id = ?",
            [$poId]
        );
        return (float) ($row['total'] ?? 0);
    }
}
