<?php
namespace App\Repositories;

class UserRepository
{
    public function findByEmail(string $guild, string $email): ?array
    {
        $guilds = $_SESSION['guilds'] ?? [];
        return $guilds[$guild]['users'][$email] ?? null;
    }

    public function create(string $guild, string $email, string $passwordHash, string $displayName, string $role, string $gameRole): void
    {
        $guilds = $_SESSION['guilds'] ?? [];
        $guilds[$guild]['users'][$email] = [
            'password' => $passwordHash,
            'display_name' => $displayName,
            'role' => $role,
            'game_role' => $gameRole
        ];
        $_SESSION['guilds'] = $guilds;
    }

    public function update(string $guild, string $email, array $data): void
    {
        $guilds = $_SESSION['guilds'] ?? [];
        if (isset($guilds[$guild]['users'][$email])) {
            $guilds[$guild]['users'][$email] = array_merge($guilds[$guild]['users'][$email], $data);
            $_SESSION['guilds'] = $guilds;
        }
    }

    public function changeEmail(string $guild, string $oldEmail, string $newEmail): void
    {
        $guilds = $_SESSION['guilds'] ?? [];
        if (isset($guilds[$guild]['users'][$oldEmail])) {
            $guilds[$guild]['users'][$newEmail] = $guilds[$guild]['users'][$oldEmail];
            unset($guilds[$guild]['users'][$oldEmail]);
            $_SESSION['guilds'] = $guilds;
        }
    }
}
