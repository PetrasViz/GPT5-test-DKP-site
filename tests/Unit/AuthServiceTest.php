<?php

namespace Tests\Unit;

use App\Services\AuthService;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $auth;
    private $users;

    protected function setUp(): void
    {
        parent::setUp();
        $this->users = $this->createMock(UserRepository::class);
        $this->auth = new AuthService($this->users);
    }

    public function testLoginReturnsUserOnValidCredentials(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->users->method('findByEmail')
            ->with($guild, $email)
            ->willReturn([
                'password' => $hash,
                'display_name' => 'User',
                'role' => 'admin',
                'game_role' => 'mage'
            ]);

        $result = $this->auth->login($guild, $email, $password);

        $this->assertSame('User', $result['display_name']);
        $this->assertSame($email, $result['email']);
        $this->assertSame($guild, $result['guild']);
    }

    public function testLoginReturnsNullWithInvalidCredentials(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';

        $this->users->method('findByEmail')
            ->with($guild, $email)
            ->willReturn(null);

        $this->assertNull($this->auth->login($guild, $email, $password));
    }

    public function testRegisterCreatesUserWhenValid(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';
        $display = 'User';
        $role = 'guild_member';
        $gameRole = 'mage';

        $this->users->expects($this->once())
            ->method('findByEmail')
            ->with($guild, $email)
            ->willReturn(null);

        $this->users->expects($this->once())
            ->method('create')
            ->with(
                $guild,
                $email,
                $this->callback(fn($hash) => password_verify($password, $hash)),
                $display,
                $role,
                $gameRole
            );

        $this->assertTrue($this->auth->register($guild, $email, $password, $display, $role, $gameRole));
    }

    public function testRegisterFailsWhenUserExists(): void
    {
        $guild = 'guild';
        $email = 'user@example.com';
        $password = 'secret';
        $display = 'User';
        $role = 'guild_member';
        $gameRole = 'mage';

        $this->users->expects($this->once())
            ->method('findByEmail')
            ->with($guild, $email)
            ->willReturn(['password' => 'hash']);

        $this->users->expects($this->never())
            ->method('create');

        $this->assertFalse($this->auth->register($guild, $email, $password, $display, $role, $gameRole));
    }
}

