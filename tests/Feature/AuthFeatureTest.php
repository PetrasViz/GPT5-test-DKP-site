<?php

namespace Tests\Feature;

use App\Services\AuthService;
use PHPUnit\Framework\TestCase;

class AuthFeatureTest extends TestCase
{
    private AuthService $auth;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $this->auth = new AuthService();
    }

    public function testUserCanRegisterAndLogin(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';
        $display = 'User';
        $role = 'guild_member';
        $gameRole = 'mage';

        $this->assertTrue($this->auth->register($guild, $email, $password, $display, $role, $gameRole));
        $this->assertArrayHasKey($email, $_SESSION['guilds'][$guild]['users']);
        $this->assertTrue(password_verify($password, $_SESSION['guilds'][$guild]['users'][$email]['password']));

        $user = $this->auth->login($guild, $email, $password);
        $this->assertSame($display, $user['display_name']);
        $this->assertSame($email, $user['email']);
        $this->assertSame($guild, $user['guild']);
    }

    public function testRegistrationFailsForExistingUser(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';

        $this->assertTrue($this->auth->register($guild, $email, $password, 'User', 'guild_member', 'mage'));
        $this->assertFalse($this->auth->register($guild, $email, $password, 'User', 'guild_member', 'mage'));
    }
}

