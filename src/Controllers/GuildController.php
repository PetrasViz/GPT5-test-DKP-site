<?php
namespace App\Controllers;

use App\Helpers\Csrf;
use App\Services\GuildService;

class GuildController
{
    private GuildService $guilds;

    public function __construct()
    {
        $this->guilds = new GuildService();
    }

    public function showRegisterForm(array $data = []): void
    {
        $errors = $data['errors'] ?? [];
        $values = $data['values'] ?? [];
        include __DIR__ . '/../views/guild/register.php';
    }

    public function index(): void
    {
        $guilds = $this->guilds->listGuilds();
        include __DIR__ . '/../views/guild/index.php';
    }

    public function register(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }
        $name = trim($_POST['name'] ?? '');
        $leaderId = (int)($_SESSION['user']['id'] ?? 0);
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Name is required';
        }
        if ($leaderId === 0) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }
        if ($errors) {
            $this->showRegisterForm([
                'errors' => $errors,
                'values' => ['name' => $name]
            ]);
            return;
        }
        $this->guilds->registerGuild($leaderId, $name);
        $users = new \App\Repositories\UserRepository();
        $users->update($_SESSION['user']['email'], ['role' => 'guild_leader']);
        $_SESSION['user']['role'] = 'guild_leader';
        header('Location: /');
        exit;
    }
}
