<?php
namespace App\Repositories;

use App\Database;
use PDO;

class EventRepository
{
    private ?PDO $db = null;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db;
    }

    private function db(): PDO
    {
        if ($this->db === null) {
            $this->db = Database::connection();
        }
        return $this->db;
    }

    public function all(): array
    {
        $stmt = $this->db()->query('SELECT id, name, occurred_at, dropped_item FROM events ORDER BY occurred_at DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            return [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'date' => substr($row['occurred_at'], 0, 10),
                'loot' => $row['dropped_item'] ? array_map('trim', explode(',', $row['dropped_item'])) : [],
            ];
        }, $rows);
    }

    public function create(string $name, string $date, array $loot = []): array
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO events (name, event_type_id, occurred_at, notes, dropped_item, created_by, created_at) '
            . 'VALUES (:name, 1, :occurred_at, NULL, :dropped, 1, CURRENT_TIMESTAMP)'
        );
        $stmt->execute([
            'name' => $name,
            'occurred_at' => $date . ' 00:00:00',
            'dropped' => $loot ? implode(', ', $loot) : null,
        ]);
        $id = (int)$this->db()->lastInsertId();
        return [
            'id' => $id,
            'name' => $name,
            'date' => $date,
            'loot' => $loot,
        ];
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM events WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
