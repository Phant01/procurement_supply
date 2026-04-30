<?php
class ReportModel extends Model {
    protected string $table = 'stock_card_entries';

    public function getRsmi(string $from, string $to): array {
        return $this->db->fetchAll(
            "SELECT * FROM v_rsmi_base
             WHERE txn_date BETWEEN ? AND ?
             ORDER BY office_name, item_name", [$from, $to]
        );
    }

    public function getRpci(): array {
        return $this->db->fetchAll(
            "SELECT * FROM v_current_stock ORDER BY item_type, item_name"
        );
    }

    public function getIcsRegistry(string $status = 'active'): array {
        $where = $status ? "WHERE status = ?" : "WHERE 1=1";
        $params = $status ? [$status] : [];
        return $this->db->fetchAll(
            "SELECT * FROM v_ics_registry $where ORDER BY assigned_to", $params
        );
    }

    public function getParRegistry(string $status = 'active'): array {
        $where = $status ? "WHERE status = ?" : "WHERE 1=1";
        $params = $status ? [$status] : [];
        return $this->db->fetchAll(
            "SELECT * FROM v_par_registry $where ORDER BY assigned_to", $params
        );
    }

    public function getDashboardStats(): array {
        $db = $this->db;
        return [
            'total_items'    => $db->fetchOne("SELECT COUNT(*) AS c FROM items WHERE is_active=1")['c'] ?? 0,
            'low_stock'      => $db->fetchOne("SELECT COUNT(*) AS c FROM v_current_stock v JOIN items i ON i.item_id=v.item_id WHERE v.balance_qty<=i.reorder_point")['c'] ?? 0,
            'pending_po'     => $db->fetchOne("SELECT COUNT(*) AS c FROM purchase_orders WHERE status='approved'")['c'] ?? 0,
            'pending_ris'    => $db->fetchOne("SELECT COUNT(*) AS c FROM ris WHERE status='pending'")['c'] ?? 0,
            'active_ics'     => $db->fetchOne("SELECT COUNT(*) AS c FROM ics WHERE status='active'")['c'] ?? 0,
            'active_par'     => $db->fetchOne("SELECT COUNT(*) AS c FROM par WHERE status='active'")['c'] ?? 0,
            'total_inv_value'=> $db->fetchOne("SELECT COALESCE(SUM(total_value),0) AS c FROM v_current_stock")['c'] ?? 0,
        ];
    }
}
