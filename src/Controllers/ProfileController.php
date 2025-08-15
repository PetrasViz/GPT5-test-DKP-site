<?php
namespace App\Controllers;

use App\Repositories\UserRepository;

class ProfileController
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function show(): void
    {
        $user = $_SESSION['user'];
        include __DIR__ . '/../views/profile/index.php';
    }

    public function update(): void
    {
        $user = $_SESSION['user'];
        $display = $_POST['display_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $gameRole = $_POST['game_role'] ?? '';

        if ($email && $email !== $user['email']) {
            $this->users->changeEmail($user['email'], $email);
            $_SESSION['user']['email'] = $email;
            $user['email'] = $email;
        }

        $data = [];
        if ($display) {
            $data['display_name'] = $display;
            $_SESSION['user']['display_name'] = $display;
        }
        if ($gameRole) {
            $data['game_role'] = $gameRole;
            $_SESSION['user']['game_role'] = $gameRole;
        }

        if ($data) {
            $this->users->update($user['email'], $data);
        }

        $this->show();
    }
}
