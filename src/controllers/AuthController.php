<?php
namespace App\Controllers;

use App\Services\AuthService;

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
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($email, $password)) {
            $_SESSION['user'] = $email;
            echo "Logged in as {$email}";
        } else {
            $this->showLoginForm(['error' => 'Invalid credentials']);
        }
    }

    public function showRegisterForm(array $data = []): void
    {
        $error = $data['error'] ?? null;
        include __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$this->auth->register($email, $password)) {
            $error = $email && $password ? 'User already exists' : 'Please fill in all fields';
            $this->showRegisterForm(['error' => $error]);
            return;
        }

        echo 'Registration successful. <a href="/login">Login</a>';
    }

    public function showForgotForm(array $data = []): void
    {
        $message = $data['message'] ?? null;
        include __DIR__ . '/../views/auth/forgot.php';
    }

    public function sendResetLink(): void
    {
        $email = $_POST['email'] ?? '';
        $message = 'If the email is registered, a reset link has been sent.';
        $this->showForgotForm(['message' => $message]);
    }
}
