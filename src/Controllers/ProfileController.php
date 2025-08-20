<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\GuildService;

class ProfileController
{
    private UserRepository $users;
    private GuildService $guilds;

    public function __construct()
    {
        $this->users = new UserRepository();
        $this->guilds = new GuildService();
    }

    public function show(): void
    {
        $user = $_SESSION['user'];
        $guilds = $this->guilds->listGuilds();
        $membership = $this->guilds->getActiveMembership($user['id']);
        $currentGuild = null;
        if ($membership) {
            $currentGuild = $this->guilds->getGuild((int)$membership['guild_id']);
        }
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

    public function joinGuild(): void
    {
        $user = $_SESSION['user'];
        $guildId = (int)($_POST['guild_id'] ?? 0);
        if ($guildId) {
            $this->guilds->joinGuild($user['id'], $guildId);
            $_SESSION['user']['role'] = 'guild_member';
        }
        header('Location: /profile');
        exit;
    }

    public function leaveGuild(): void
    {
        $user = $_SESSION['user'];
        $this->guilds->leaveGuild($user['id']);
        $this->users->update($user['email'], ['role' => 'guild_member']);
        $_SESSION['user']['role'] = 'guild_member';
        header('Location: /profile');
        exit;
    }
}
