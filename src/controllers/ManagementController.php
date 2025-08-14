<?php
namespace App\Controllers;

use App\Repositories\AuctionRepository;
use App\Repositories\EventRepository;

class ManagementController
{
    private AuctionRepository $auctions;
    private EventRepository $events;

    public function __construct()
    {
        $this->auctions = new AuctionRepository();
        $this->events = new EventRepository();
    }

    public function index(): void
    {
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
}
