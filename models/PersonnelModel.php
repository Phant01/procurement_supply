<?php
class PersonnelModel extends Model {
    protected string $table      = 'personnel';
    protected string $primaryKey = 'personnel_id';

    public function getWithOffice(): array {
        return $this->db->fetchAll(
            "SELECT p.*, o.office_name FROM personnel p
             JOIN offices o ON o.office_id = p.office_id
             WHERE p.is_active = 1
             ORDER BY p.full_name"
        );
    }

    public function getByOffice(int $officeId): array {
        return $this->findWhere('office_id = ? AND is_active = 1', [$officeId], 'full_name');
    }
}
