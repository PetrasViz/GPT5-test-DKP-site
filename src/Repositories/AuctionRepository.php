<?php
namespace App\Repositories;

use App\Database;
use PDO;

class AuctionRepository
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
        $stmt = $this->db()->query('SELECT * FROM auctions ORDER BY created_at DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            return [
                'id' => (int)$row['id'],
                'item' => $row['item_name'],
                'status' => strtolower($row['status']),
                'created_at' => $row['created_at'],
                'min_bid' => (int)$row['min_bid'],
                'end_time' => strtotime($row['ends_at']),
                'winner' => $row['winner_user_id'],
                'event_id' => $row['event_id'],
            ];
        }, $rows);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM auctions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return [
            'id' => (int)$row['id'],
            'item' => $row['item_name'],
            'status' => strtolower($row['status']),
            'created_at' => $row['created_at'],
            'min_bid' => (int)$row['min_bid'],
            'end_time' => strtotime($row['ends_at']),
            'winner' => $row['winner_user_id'],
            'event_id' => $row['event_id'],
        ];
    }

    public function create(string $item, int $eventId, float $minBid = 0, int $durationMinutes = 60): array
    {
        $end = time() + ($durationMinutes * 60);
        $stmt = $this->db()->prepare('INSERT INTO auctions (event_id, item_name, min_bid, ends_at, status, created_at) VALUES (:event_id, :item, :min_bid, :ends_at, "OPEN", CURRENT_TIMESTAMP)');
        $stmt->execute([
            'event_id' => $eventId,
            'item' => $item,
            'min_bid' => $minBid,
            'ends_at' => date('Y-m-d H:i:s', $end),
        ]);
        $id = (int)$this->db()->lastInsertId();
        return [
            'id' => $id,
            'item' => $item,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s'),
            'min_bid' => $minBid,
            'end_time' => $end,
            'winner' => null,
            'event_id' => $eventId,
        ];
    }

    public function close(int $id): void
    {
        $stmt = $this->db()->prepare('UPDATE auctions SET status = "CLOSED", closed_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM auctions WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function setWinner(int $id, int $winnerUserId): void
    {
        $stmt = $this->db()->prepare('UPDATE auctions SET winner_user_id = :winner, status = "CLOSED", closed_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['winner' => $winnerUserId, 'id' => $id]);
    }

    public function setMinBid(int $id, float $minBid): void
    {
        $stmt = $this->db()->prepare('UPDATE auctions SET min_bid = :min_bid WHERE id = :id');
        $stmt->execute(['min_bid' => $minBid, 'id' => $id]);
    }

    public function setEndTime(int $id, int $timestamp): void
    {
        $stmt = $this->db()->prepare('UPDATE auctions SET ends_at = :end WHERE id = :id');
        $stmt->execute(['end' => date('Y-m-d H:i:s', $timestamp), 'id' => $id]);
    }
}
