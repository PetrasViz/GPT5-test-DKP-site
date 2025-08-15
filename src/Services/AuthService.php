<?php
namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    private UserRepository $users;

    public function __construct(?UserRepository $users = null)
    {
        $this->users = $users ?? new UserRepository();
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->users->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user + ['email' => $email];
        }
        return null;
    }

    public function register(string $email, string $password, string $displayName, string $role, string $gameRole): bool
    {
        if (!$email || !$password || $this->users->findByEmail($email)) {
            return false;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->users->create($email, $passwordHash, $displayName, $role, $gameRole);
        return true;
    }
}

