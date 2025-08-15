<?php
namespace App\Repositories;

use App\Database;
use PDO;

class GuildRepository
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

    public function createGuild(string $name, int $leaderId): int
    {
        $stmt = $this->db()->prepare('INSERT INTO guilds (name, leader_id, last_leader_transfer_at, created_at, updated_at) VALUES (:name, :leader, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $stmt->execute(['name' => $name, 'leader' => $leaderId]);
        $id = (int)$this->db()->lastInsertId();
        $this->addMember($id, $leaderId);
        return $id;
    }

    public function getGuild(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM guilds WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $guild = $stmt->fetch(PDO::FETCH_ASSOC);
        return $guild ?: null;
    }

    public function addMember(int $guildId, int $userId): void
    {
        $stmt = $this->db()->prepare('INSERT INTO guild_members (guild_id, user_id, joined_at) VALUES (:guild, :user, CURRENT_TIMESTAMP)');
        $stmt->execute(['guild' => $guildId, 'user' => $userId]);
    }

    public function getActiveMembership(int $userId): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM guild_members WHERE user_id = :user AND left_at IS NULL ORDER BY joined_at DESC LIMIT 1');
        $stmt->execute(['user' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getLastMembership(int $userId): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM guild_members WHERE user_id = :user ORDER BY joined_at DESC LIMIT 1');
        $stmt->execute(['user' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function isMember(int $guildId, int $userId): bool
    {
        $stmt = $this->db()->prepare('SELECT 1 FROM guild_members WHERE guild_id = :guild AND user_id = :user AND left_at IS NULL');
        $stmt->execute(['guild' => $guildId, 'user' => $userId]);
        return (bool)$stmt->fetchColumn();
    }

    public function updateLeader(int $guildId, int $newLeaderId): void
    {
        $stmt = $this->db()->prepare('UPDATE guilds SET leader_id = :leader, last_leader_transfer_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['leader' => $newLeaderId, 'id' => $guildId]);
    }
}
