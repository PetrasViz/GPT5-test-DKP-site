<?php
namespace App\Repositories;

class EventRepository
{
    public function all(): array
    {
        $events = $_SESSION['events'] ?? [];
        if (!$events) {
            for ($i = 1; $i <= 30; $i++) {
                $events[] = [
                    'id' => $i,
                    'name' => 'Event ' . $i,
                    'date' => date('Y-m-d', strtotime("-{$i} days"))
                ];
            }
            $_SESSION['events'] = $events;
        }
        return $events;
    }
}
