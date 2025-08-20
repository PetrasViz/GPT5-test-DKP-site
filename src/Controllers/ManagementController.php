<?php
namespace App\Controllers;

use App\Repositories\AuctionRepository;
use App\Repositories\EventRepository;
use App\Helpers\Csrf;
use App\Services\GuildService;

class ManagementController
{
    private AuctionRepository $auctions;
    private EventRepository $events;
    private GuildService $guilds;

    public function __construct()
    {
        $this->auctions = new AuctionRepository();
        $this->events = new EventRepository();
        $this->guilds = new GuildService();
    }

    public function index(): void
    {
        $user = $_SESSION['user'];
        $membership = $this->guilds->getActiveMembership($user['id']);
        $currentGuild = null;
        if ($membership) {
            $currentGuild = $this->guilds->getGuild((int)$membership['guild_id']);
        }

        // Auctions
        $auctionPage = max(1, (int)($_GET['auction_page'] ?? 1));
        $auctionSort = $_GET['auction_sort'] ?? 'id';
        $auctionDir = $_GET['auction_dir'] ?? 'asc';

        $perPage = 5;
        $auctionItems = $this->auctions->all();
        usort($auctionItems, function ($a, $b) use ($auctionSort, $auctionDir) {
            return $auctionDir === 'asc' ? $a[$auctionSort] <=> $b[$auctionSort] : $b[$auctionSort] <=> $a[$auctionSort];
        });
        $auctionTotal = count($auctionItems);
        $auctionPages = max(1, (int)ceil($auctionTotal / $perPage));
        $auctionPage = min($auctionPage, $auctionPages);
        $auctionItems = array_slice($auctionItems, ($auctionPage - 1) * $perPage, $perPage);

        // Events
        $eventPage = max(1, (int)($_GET['event_page'] ?? 1));
        $eventSort = $_GET['event_sort'] ?? 'date';
        $eventDir = $_GET['event_dir'] ?? 'desc';

        $eventItems = $this->events->all();
        usort($eventItems, function ($a, $b) use ($eventSort, $eventDir) {
            return $eventDir === 'asc' ? $a[$eventSort] <=> $b[$eventSort] : $b[$eventSort] <=> $a[$eventSort];
        });
        $eventTotal = count($eventItems);
        $eventPages = max(1, (int)ceil($eventTotal / $perPage));
        $eventPage = min($eventPage, $eventPages);
        $eventItems = array_slice($eventItems, ($eventPage - 1) * $perPage, $perPage);

        include __DIR__ . '/../views/management/index.php';
    }

    public function updateMotd(): void
    {
        if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(400);
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /management');
            exit;
        }
        $user = $_SESSION['user'];
        $motd = trim($_POST['motd'] ?? '');
        $membership = $this->guilds->getActiveMembership($user['id']);
        if (!$membership) {
            $_SESSION['error'] = 'You are not part of a guild';
            header('Location: /management');
            exit;
        }
        $guild = $this->guilds->getGuild((int)$membership['guild_id']);
        if ($user['role'] !== 'ADMIN' && (int)$guild['leader_id'] !== (int)$user['id']) {
            http_response_code(403);
            $_SESSION['error'] = 'Forbidden';
            header('Location: /management');
            exit;
        }
        $this->guilds->setMotd((int)$membership['guild_id'], $motd);
        $_SESSION['success'] = 'Message of the day updated';
        header('Location: /management');
        exit;
    }
}
