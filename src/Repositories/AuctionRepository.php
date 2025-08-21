<?php
namespace App\Repositories;

class AuctionRepository
{
    public function all(): array
    {
        $auctions = $_SESSION['auctions'] ?? [];
        if (!$auctions) {
            for ($i = 1; $i <= 25; $i++) {
                $auctions[] = [
                    'id' => $i,
                    'item' => 'Item ' . $i,
                    'status' => $i % 2 ? 'open' : 'closed',
                    'created_at' => date('Y-m-d', strtotime("-{$i} days")),
                    'min_bid' => 0,
                    'end_time' => time() + 3600,
                    'winner' => null,
                    'event_id' => null,
                ];
            }
            $_SESSION['auctions'] = $auctions;
        }
        return $auctions;
    }

    private function save(array $auctions): void
    {
        $_SESSION['auctions'] = $auctions;
    }

    private function findIndex(int $id): ?int
    {
        foreach ($this->all() as $index => $auction) {
            if ((int)$auction['id'] === $id) {
                return $index;
            }
        }
        return null;
    }

    public function find(int $id): ?array
    {
        $index = $this->findIndex($id);
        return $index !== null ? $this->all()[$index] : null;
    }

    public function create(string $item, int $eventId = null, float $minBid = 0, int $durationMinutes = 60): array
    {
        $auctions = $this->all();
        $id = $auctions ? max(array_column($auctions, 'id')) + 1 : 1;
        $auction = [
            'id' => $id,
            'item' => $item,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s'),
            'min_bid' => $minBid,
            'end_time' => time() + ($durationMinutes * 60),
            'winner' => null,
            'event_id' => $eventId,
        ];
        $auctions[] = $auction;
        $this->save($auctions);
        return $auction;
    }

    public function close(int $id): void
    {
        $index = $this->findIndex($id);
        if ($index !== null) {
            $auctions = $this->all();
            $auctions[$index]['status'] = 'closed';
            $this->save($auctions);
        }
    }

    public function delete(int $id): void
    {
        $auctions = array_values(array_filter($this->all(), fn($a) => (int)$a['id'] !== $id));
        $this->save($auctions);
    }

    public function setWinner(int $id, string $winner): void
    {
        $index = $this->findIndex($id);
        if ($index !== null) {
            $auctions = $this->all();
            $auctions[$index]['winner'] = $winner;
            $auctions[$index]['status'] = 'closed';
            $this->save($auctions);
        }
    }

    public function setMinBid(int $id, float $minBid): void
    {
        $index = $this->findIndex($id);
        if ($index !== null) {
            $auctions = $this->all();
            $auctions[$index]['min_bid'] = $minBid;
            $this->save($auctions);
        }
    }

    public function setEndTime(int $id, int $timestamp): void
    {
        $index = $this->findIndex($id);
        if ($index !== null) {
            $auctions = $this->all();
            $auctions[$index]['end_time'] = $timestamp;
            $this->save($auctions);
        }
    }
}
