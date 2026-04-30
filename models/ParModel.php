<?php
class ParModel extends Model {
    protected string $table      = 'par';
    protected string $primaryKey = 'par_id';

    public function getRegistry(): array {
        return $this->db->fetchAll("SELECT * FROM v_par_registry ORDER BY assigned_to");
    }

    public function getWithDetails(int $id): array|false {
        return $this->db->fetchOne("SELECT * FROM v_par_registry WHERE par_id = ?", [$id]);
    }

    public function generateFromRisItem(int $risItemId, int $personnelId,
                                        float $qty, float $unitCost,
                                        string $date, string $propertyNo,
                                        string $serialNo = '', string $brandModel = '',
                                        string $location = ''): int {
        $parNo = 'PAR-' . date('Ymd') . '-' . str_pad($risItemId, 5, '0', STR_PAD_LEFT);
        return $this->create([
            'ris_item_id'  => $risItemId,
            'personnel_id' => $personnelId,
            'par_number'   => $parNo,
            'par_date'     => $date,
            'quantity'     => $qty,
            'unit_cost'    => $unitCost,
            'property_no'  => $propertyNo,
            'serial_no'    => $serialNo,
            'brand_model'  => $brandModel,
            'location'     => $location,
        ]);
    }
}
