<?php
class IcsModel extends Model {
    protected string $table      = 'ics';
    protected string $primaryKey = 'ics_id';

    public function getRegistry(): array {
        return $this->db->fetchAll("SELECT * FROM v_ics_registry ORDER BY assigned_to");
    }

    public function getWithDetails(int $id): array|false {
        return $this->db->fetchOne("SELECT * FROM v_ics_registry WHERE ics_id = ?", [$id]);
    }

    public function generateFromRisItem(int $risItemId, int $personnelId,
                                        float $qty, float $unitCost,
                                        string $date, string $propertyNo,
                                        string $location = ''): int {
        $icsNo = 'ICS-' . date('Ymd') . '-' . str_pad($risItemId, 5, '0', STR_PAD_LEFT);
        return $this->create([
            'ris_item_id'  => $risItemId,
            'personnel_id' => $personnelId,
            'ics_number'   => $icsNo,
            'ics_date'     => $date,
            'quantity'     => $qty,
            'unit_cost'    => $unitCost,
            'property_no'  => $propertyNo,
            'location'     => $location,
        ]);
    }
}
