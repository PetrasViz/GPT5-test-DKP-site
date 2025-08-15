<?php
namespace App\Controllers;

use App\Repositories\AuctionRepository;

class AuctionController
{
    private AuctionRepository $auctions;

    public function __construct()
    {
        $this->auctions = new AuctionRepository();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'id';
        $dir = $_GET['dir'] ?? 'asc';
        $perPage = 10;

        $items = $this->auctions->all();
        usort($items, function ($a, $b) use ($sort, $dir) {
            return $dir === 'asc' ? $a[$sort] <=> $b[$sort] : $b[$sort] <=> $a[$sort];
        });

        $total = count($items);
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($items, $offset, $perPage);

        include __DIR__ . '/../views/auctions/index.php';
    }
}
