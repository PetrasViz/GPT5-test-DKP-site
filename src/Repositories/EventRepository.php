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
                    'date' => date('Y-m-d', strtotime("-{$i} days")),
                    'loot' => [],
                ];
            }
            $_SESSION['events'] = $events;
        }
        return $events;
    }

    private function save(array $events): void
    {
        $_SESSION['events'] = $events;
    }

    public function create(string $name, string $date, array $loot = []): array
    {
        $events = $this->all();
        $id = $events ? max(array_column($events, 'id')) + 1 : 1;
        $event = [
            'id' => $id,
            'name' => $name,
            'date' => $date,
            'loot' => $loot,
        ];
        $events[] = $event;
        $this->save($events);
        return $event;
    }

    public function delete(int $id): void
    {
        $events = array_values(array_filter($this->all(), fn($e) => (int)$e['id'] !== $id));
        $this->save($events);
    }
}
