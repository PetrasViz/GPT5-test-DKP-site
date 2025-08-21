<?php
namespace Tests\Unit;

use App\Repositories\EventRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class EventRepositoryTest extends TestCase
{
    private EventRepository $repo;

    protected function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            event_type_id INTEGER NOT NULL DEFAULT 1,
            occurred_at TEXT NOT NULL,
            notes TEXT NULL,
            dropped_item TEXT NULL,
            created_by INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL
        )');
        $this->repo = new EventRepository($pdo);
    }

    public function testCreateAndDeleteEvent(): void
    {
        $event = $this->repo->create('Raid', '2024-01-01', ['Sword', 'Shield']);
        $this->assertSame('Raid', $event['name']);
        $this->repo->delete($event['id']);
        $ids = array_column($this->repo->all(), 'id');
        $this->assertNotContains($event['id'], $ids);
    }
}
