<?php
class SupplierModel extends Model {
    protected string $table      = 'suppliers';
    protected string $primaryKey = 'supplier_id';

    public function getActive(): array {
        return $this->findWhere('is_active = 1', [], 'supplier_name ASC');
    }

    public function searchByName(string $q): array {
        return $this->findWhere('supplier_name LIKE ? AND is_active = 1',
            ["%$q%"], 'supplier_name');
    }
}
