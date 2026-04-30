<?php
class UserModel extends Model {
    protected string $table      = 'users';
    protected string $primaryKey = 'user_id';

    public function findByUsername(string $username): array|false {
        return $this->findOneWhere('username = ?', [$username]);
    }

    public function createUser(array $data): int {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->create($data);
    }

    public function changePassword(int $id, string $newPassword): bool {
        return $this->update($id, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);
    }

    public function listUsers(): array {
        return $this->db->fetchAll(
            "SELECT u.*, p.full_name as personnel_name, o.office_name
             FROM users u
             LEFT JOIN personnel p ON p.personnel_id = u.personnel_id
             LEFT JOIN offices o ON o.office_id = p.office_id
             ORDER BY u.full_name"
        );
    }
}
