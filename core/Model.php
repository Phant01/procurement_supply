<?php
abstract class Model {
    protected Database $db;
    protected string $table      = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(string $orderBy = '', int $limit = 0): array {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        if ($limit)   $sql .= " LIMIT $limit";
        return $this->db->fetchAll($sql);
    }

    public function findById(int $id): array|false {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    public function findWhere(string $conditions, array $params = [], string $orderBy = ''): array {
        $sql = "SELECT * FROM {$this->table} WHERE $conditions";
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        return $this->db->fetchAll($sql, $params);
    }

    public function findOneWhere(string $conditions, array $params = []): array|false {
        $sql = "SELECT * FROM {$this->table} WHERE $conditions LIMIT 1";
        return $this->db->fetchOne($sql, $params);
    }

    public function create(array $data): int {
        $cols   = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($cols) VALUES ($placeholders)";
        $this->db->execute($sql, array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?";
        $this->db->execute($sql, [...array_values($data), $id]);
        return true;
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->db->execute($sql, [$id]);
        return true;
    }

    public function count(string $conditions = '', array $params = []): int {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
        if ($conditions) $sql .= " WHERE $conditions";
        $row = $this->db->fetchOne($sql, $params);
        return (int) ($row['cnt'] ?? 0);
    }

    public function paginate(int $page, int $perPage, string $conditions = '',
                             array $params = [], string $orderBy = ''): array {
        $offset    = ($page - 1) * $perPage;
        $total     = $this->count($conditions, $params);
        $totalPages = (int) ceil($total / $perPage);

        $sql = "SELECT * FROM {$this->table}";
        if ($conditions) $sql .= " WHERE $conditions";
        if ($orderBy)    $sql .= " ORDER BY $orderBy";
        $sql .= " LIMIT ? OFFSET ?";
        $rows = $this->db->fetchAll($sql, [...$params, $perPage, $offset]);

        return compact('rows', 'total', 'totalPages', 'page', 'perPage');
    }

    public function query(string $sql, array $params = []): array {
        return $this->db->fetchAll($sql, $params);
    }

    public function queryOne(string $sql, array $params = []): array|false {
        return $this->db->fetchOne($sql, $params);
    }

    public function execute(string $sql, array $params = []): bool {
        return $this->db->execute($sql, $params);
    }
}
