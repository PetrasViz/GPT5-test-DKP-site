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
        $email = trim($_POST['email'] ?? '');
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            $_SESSION['error'] = 'Invalid CSRF token';
            $this->showLoginForm(['values' => ['email' => $email]]);
            return;
        }
        $password = $_POST['password'] ?? '';

        $errors = [];
        if ($email === '') {
            $errors['email'] = 'Email is required';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required';
        }

        if ($errors) {
            $this->showLoginForm([
                'errors' => $errors,
                'values' => ['email' => $email]
            ]);
            return;
        }

        $user = $this->auth->login($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit;
        }

        $_SESSION['error'] = 'Invalid credentials';
        $this->showLoginForm([
            'values' => ['email' => $email]
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
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $display = trim($_POST['display_name'] ?? '');
        $role = 'guild_member'; // Elevated roles must be granted by an administrator
        $gameRole = $_POST['game_role'] ?? '';
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            $_SESSION['error'] = 'Invalid CSRF token';
            $this->showRegisterForm([
                'values' => [
                    'email' => $email,
                    'display_name' => $display,
                    'game_role' => $gameRole
                ]
            ]);
            return;
        }

        $errors = [];
        if ($email === '') {
            $errors['email'] = 'Email is required';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required';
        }
        if ($display === '') {
            $errors['display_name'] = 'Display name is required';
        }
        if ($gameRole === '') {
            $errors['game_role'] = 'In-game role is required';
        }

        if ($errors) {
            $this->showRegisterForm([
                'errors' => $errors,
                'values' => [
                    'email' => $email,
                    'display_name' => $display,
                    'game_role' => $gameRole
                ]
            ]);
            return;
        }

        if (!$this->auth->register($email, $password, $display, $gameRole, $role)) {
            $this->showRegisterForm([
                'errors' => ['email' => 'User already exists'],
                'values' => [
                    'email' => $email,
                    'display_name' => $display,
                    'game_role' => $gameRole
                ]
            ]);
            return;
        }

        $_SESSION['success'] = 'Registration successful. Please login.';
        header('Location: /login');
        exit;
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
            $_SESSION['error'] = 'Invalid CSRF token';
            $this->showForgotForm();
            return;
        }
        $email = $_POST['email'] ?? '';
        $message = 'If the email is registered, a reset link has been sent.';
        $this->showForgotForm(['message' => $message]);
    }
}
