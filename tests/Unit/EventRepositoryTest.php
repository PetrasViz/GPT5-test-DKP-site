<?php
namespace Tests\Unit;

use App\Repositories\EventRepository;
use PHPUnit\Framework\TestCase;

class EventRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testCreateAndDeleteEvent(): void
    {
        $repo = new EventRepository();
        $event = $repo->create('Raid', '2024-01-01', ['Sword', 'Shield']);
        $this->assertSame('Raid', $event['name']);
        $repo->delete($event['id']);
        $ids = array_column($repo->all(), 'id');
        $this->assertNotContains($event['id'], $ids);
    }
}
