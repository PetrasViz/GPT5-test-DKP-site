<?php
class AuthController {
    public function showLoginForm(array $data = []): void {
        $error = $data['error'] ?? null;
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $users = $_SESSION['users'] ?? [];

        if (isset($users[$email]) && $users[$email]['password'] === $password) {
            $_SESSION['user'] = $email;
            echo "Logged in as {$email}";
        } else {
            $this->showLoginForm(['error' => 'Invalid credentials']);
        }
    }

    public function showRegisterForm(array $data = []): void {
        $error = $data['error'] ?? null;
        include __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->showRegisterForm(['error' => 'Please fill in all fields']);
            return;
        }

        $users = $_SESSION['users'] ?? [];
        if (isset($users[$email])) {
            $this->showRegisterForm(['error' => 'User already exists']);
            return;
        }

        $users[$email] = ['password' => $password];
        $_SESSION['users'] = $users;

        echo 'Registration successful. <a href="/login">Login</a>';
    }

    public function showForgotForm(array $data = []): void {
        $message = $data['message'] ?? null;
        include __DIR__ . '/../views/auth/forgot.php';
    }

    public function sendResetLink(): void {
        $email = $_POST['email'] ?? '';
        $message = 'If the email is registered, a reset link has been sent.';
        $this->showForgotForm(['message' => $message]);
    }
}
