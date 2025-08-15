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
                    'created_at' => date('Y-m-d', strtotime("-{$i} days"))
                ];
            }
            $_SESSION['auctions'] = $auctions;
        }
        return $auctions;
    }
}
