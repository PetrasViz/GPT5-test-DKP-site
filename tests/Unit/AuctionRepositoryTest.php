<?php
namespace Tests\Unit;

use App\Repositories\AuctionRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class AuctionRepositoryTest extends TestCase
{
    private AuctionRepository $repo;

    protected function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE auctions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            event_id INTEGER NOT NULL,
            item_name TEXT NOT NULL,
            min_bid INTEGER NOT NULL,
            ends_at TEXT NOT NULL,
            status TEXT NOT NULL,
            winner_user_id INTEGER NULL,
            winning_bid INTEGER NULL,
            created_at TEXT NOT NULL,
            closed_at TEXT NULL
        )');
        $this->repo = new AuctionRepository($pdo);
    }

    public function testCreateAndUpdateAuction(): void
    {
        $auction = $this->repo->create('Sword', 1, 5, 60);
        $this->assertSame('Sword', $auction['item']);
        $this->repo->setMinBid($auction['id'], 10);
        $this->repo->setEndTime($auction['id'], time() + 120);
        $this->repo->setWinner($auction['id'], 2);
        $this->repo->close($auction['id']);
        $this->repo->delete($auction['id']);
        $this->assertNull($this->repo->find($auction['id']));
    }
}
