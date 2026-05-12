<?php
class Model {
    protected mysqli $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    protected function query(string $sql): mysqli_result|bool {
        return mysqli_query($this->db, $sql);
    }

    protected function prepare(string $sql): mysqli_stmt {
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) die('Prepare failed: ' . mysqli_error($this->db));
        return $stmt;
    }

    protected function fetchAll(mysqli_result|bool $result): array {
        if (!$result) return [];
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
        return $rows;
    }

    protected function fetchOne(mysqli_result|bool $result): ?array {
        if (!$result) return null;
        return mysqli_fetch_assoc($result) ?: null;
    }

    protected function insertId(): int {
        return (int) mysqli_insert_id($this->db);
    }

    protected function escape(string $str): string {
        return mysqli_real_escape_string($this->db, $str);
    }

    protected function countQuery(string $sql): int {
        $res = $this->query($sql);
        return (int) ($this->fetchOne($res)['t'] ?? 0);
    }
}
