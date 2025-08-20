<?php
namespace Tests\Unit;

use App\Repositories\GuildRepository;
use App\Services\GuildService;
use PHPUnit\Framework\TestCase;

class AdminGuildServiceTest extends TestCase
{
    public function testListGuildsReturnsRepositoryData(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);
        $repo->expects($this->once())->method('getAllGuilds')->willReturn([
            ['id' => 1, 'name' => 'Guild A']
        ]);
        $this->assertSame([
            ['id' => 1, 'name' => 'Guild A']
        ], $service->listGuilds());
    }

    public function testAdminJoinGuildAsLeaderUpdatesLeader(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);
        $repo->method('getActiveMembership')->with(5)->willReturn(null);
        $repo->expects($this->once())->method('addMember')->with(3, 5);
        $repo->expects($this->once())->method('updateLeader')->with(3, 5);
        $this->assertTrue($service->adminJoinGuild(3, 5, true));
    }

    public function testBlockGuildCallsRepository(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);
        $repo->expects($this->once())->method('blockGuild')->with(7);
        $service->blockGuild(7);
    }
}
