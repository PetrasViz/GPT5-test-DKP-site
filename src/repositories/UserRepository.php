<?php
namespace App\Repositories;

class UserRepository
{
    public function findByEmail(string $email): ?array
    {
        $users = $_SESSION['users'] ?? [];
        return $users[$email] ?? null;
    }

    public function create(string $email, string $password): void
    {
        $users = $_SESSION['users'] ?? [];
        $users[$email] = ['password' => $password];
        $_SESSION['users'] = $users;
    }
}

