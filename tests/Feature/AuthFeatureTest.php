<?php

namespace Tests\Feature;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use PDO;
use PHPUnit\Framework\TestCase;

class AuthFeatureTest extends TestCase
{
    private AuthService $auth;

    protected function setUp(): void
    {
        parent::setUp();

        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            display_name TEXT NOT NULL,
            role TEXT NOT NULL,
            game_role TEXT NULL,
            is_active INTEGER NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        )');

        $repo = new UserRepository($pdo);
        $this->auth = new AuthService($repo);
    }

    public function testUserCanRegisterAndLogin(): void
    {
        $email = 'user@example.com';
        $password = 'secret';
        $display = 'User';
        $role = 'guild_member';
        $gameRole = 'mage';

        $this->assertTrue($this->auth->register($email, $password, $display, $role, $gameRole));

        $user = $this->auth->login($email, $password);
        $this->assertSame($display, $user['display_name']);
        $this->assertSame($email, $user['email']);
    }

    public function testRegistrationFailsForExistingUser(): void
    {
        $email = 'user@example.com';
        $password = 'secret';
        $this->assertTrue($this->auth->register($email, $password, 'User', 'guild_member', 'mage'));
        $this->assertFalse($this->auth->register($email, $password, 'User', 'guild_member', 'mage'));
    }
}

