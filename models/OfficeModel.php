<?php
class OfficeModel extends Model {
    protected string $table      = 'offices';
    protected string $primaryKey = 'office_id';

    public function getActive(): array {
        return $this->findWhere('is_active = 1', [], 'office_name ASC');
    }

    public function getActiveWithDept(): array {
        return $this->db->fetchAll(
            "SELECT office_id, office_name, department, office_code,
                    CONCAT(
                        CASE WHEN department != '' AND department IS NOT NULL
                             THEN CONCAT(department, ' - ')
                             ELSE ''
                        END,
                        office_name
                    ) AS display_name
             FROM offices
             WHERE is_active = 1
             ORDER BY department ASC, office_name ASC"
        );
    }
}