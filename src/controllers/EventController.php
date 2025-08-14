<?php
namespace App\Controllers;

use App\Repositories\EventRepository;

class EventController
{
    private EventRepository $events;

    public function __construct()
    {
        $this->events = new EventRepository();
    }

    public function history(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'date';
        $dir = $_GET['dir'] ?? 'desc';
        $perPage = 10;

        $items = $this->events->all();
        usort($items, function ($a, $b) use ($sort, $dir) {
            return $dir === 'asc' ? $a[$sort] <=> $b[$sort] : $b[$sort] <=> $a[$sort];
        });

        $total = count($items);
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($items, $offset, $perPage);

        include __DIR__ . '/../views/events/history.php';
    }
}
