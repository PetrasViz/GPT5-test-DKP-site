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
        $error = $data['error'] ?? null;
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $guild = $_POST['guild'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->auth->login($guild, $email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit;
        }
        $this->showLoginForm(['error' => 'Invalid credentials']);
    }

    public function showRegisterForm(array $data = []): void
    {
        $error = $data['error'] ?? null;
        include __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $guild = $_POST['guild'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $display = $_POST['display_name'] ?? '';
        $role = $_POST['role'] ?? 'guild_member';
        $gameRole = $_POST['game_role'] ?? '';

        if (!$this->auth->register($guild, $email, $password, $display, $role, $gameRole)) {
            $error = $guild && $email && $password ? 'User already exists' : 'Please fill in all fields';
            $this->showRegisterForm(['error' => $error]);
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
