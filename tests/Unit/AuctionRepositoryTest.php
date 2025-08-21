<?php
namespace Tests\Unit;

use App\Repositories\AuctionRepository;
use PHPUnit\Framework\TestCase;

class AuctionRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testCreateAndUpdateAuction(): void
    {
        $repo = new AuctionRepository();
        $auction = $repo->create('Sword', 1, 5, 60);
        $this->assertSame('Sword', $auction['item']);
        $repo->setMinBid($auction['id'], 10);
        $repo->setEndTime($auction['id'], time() + 120);
        $repo->setWinner($auction['id'], 'Alice');
        $repo->close($auction['id']);
        $repo->delete($auction['id']);
        $this->assertNull($repo->find($auction['id']));
    }
}
