<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Helpers\Csrf;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function showLoginForm(array $data = []): void
    {
        $errors = $data['errors'] ?? [];
        $values = $data['values'] ?? [];
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $guild = trim($_POST['guild'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];
        if ($guild === '') {
            $errors['guild'] = 'Guild is required';
        }
        if ($email === '') {
            $errors['email'] = 'Email is required';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required';
        }

        if ($errors) {
            $this->showLoginForm([
                'errors' => $errors,
                'values' => ['guild' => $guild, 'email' => $email]
            ]);
            return;
        }

        $user = $this->auth->login($guild, $email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit;
        }

        $this->showLoginForm([
            'errors' => ['general' => 'Invalid credentials'],
            'values' => ['guild' => $guild, 'email' => $email]
        ]);
    }

    public function showRegisterForm(array $data = []): void
    {
        $errors = $data['errors'] ?? [];
        $values = $data['values'] ?? [];
        include __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $guild = trim($_POST['guild'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $display = trim($_POST['display_name'] ?? '');
        $role = $_POST['role'] ?? 'guild_member';
        $gameRole = $_POST['game_role'] ?? '';

        $errors = [];
        if ($guild === '') {
            $errors['guild'] = 'Guild is required';
        }
        if ($email === '') {
            $errors['email'] = 'Email is required';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required';
        }
        if ($display === '') {
            $errors['display_name'] = 'Display name is required';
        }
        if ($role === '') {
            $errors['role'] = 'Role is required';
        }
        if ($gameRole === '') {
            $errors['game_role'] = 'In-game role is required';
        }

        if ($errors) {
            $this->showRegisterForm([
                'errors' => $errors,
                'values' => [
                    'guild' => $guild,
                    'email' => $email,
                    'display_name' => $display,
                    'role' => $role,
                    'game_role' => $gameRole
                ]
            ]);
            return;
        }

        if (!$this->auth->register($guild, $email, $password, $display, $role, $gameRole)) {
            $this->showRegisterForm([
                'errors' => ['email' => 'User already exists'],
                'values' => [
                    'guild' => $guild,
                    'email' => $email,
                    'display_name' => $display,
                    'role' => $role,
                    'game_role' => $gameRole
                ]
            ]);
            return;
        }

        echo 'Registration successful. <a href="/login">Login</a>';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function showForgotForm(array $data = []): void
    {
        $message = $data['message'] ?? null;
        include __DIR__ . '/../views/auth/forgot.php';
    }

    public function sendResetLink(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $email = $_POST['email'] ?? '';
        $message = 'If the email is registered, a reset link has been sent.';
        $this->showForgotForm(['message' => $message]);
    }
}
