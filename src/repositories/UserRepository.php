<?php
namespace App\Repositories;

use App\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    public function findByEmail(string $guild, string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return [
            'password' => $user['password_hash'],
            'display_name' => $user['display_name'],
            'role' => $user['role'],
            'game_role' => $user['game_role'],
            'is_active' => $user['is_active'],
        ];
    }

    public function create(string $guild, string $email, string $passwordHash, string $displayName, string $role, string $gameRole): void
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, password_hash, display_name, role, game_role, is_active, created_at, updated_at) VALUES (:email, :password_hash, :display_name, :role, :game_role, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $stmt->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
            'display_name' => $displayName,
            'role' => $role,
            'game_role' => $gameRole,
        ]);
    }

    public function update(string $guild, string $email, array $data): void
    {
        if (empty($data)) {
            return;
        }

        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ', updated_at = CURRENT_TIMESTAMP WHERE email = :email';
        $data['email'] = $email;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function changeEmail(string $guild, string $oldEmail, string $newEmail): void
    {
        $stmt = $this->db->prepare('UPDATE users SET email = :new_email, updated_at = CURRENT_TIMESTAMP WHERE email = :old_email');
        $stmt->execute([
            'new_email' => $newEmail,
            'old_email' => $oldEmail,
        ]);
    }
}
