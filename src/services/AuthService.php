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

    public function login(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);
        return $user && $user['password'] === $password;
    }

    public function register(string $email, string $password): bool
    {
        if (!$email || !$password || $this->users->findByEmail($email)) {
            return false;
        }
        $this->users->create($email, $password);
        return true;
    }
}

