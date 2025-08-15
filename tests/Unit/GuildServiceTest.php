<?php

namespace Tests\Unit;

use App\Repositories\GuildRepository;
use App\Services\GuildService;
use PHPUnit\Framework\TestCase;

class GuildServiceTest extends TestCase
{
    public function testRegisterGuildCreatesGuild(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->expects($this->once())
            ->method('createGuild')
            ->with('Guild One', 1)
            ->willReturn(10);

        $this->assertSame(10, $service->registerGuild(1, 'Guild One'));
    }

    public function testAddMemberRespectsCooldown(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getGuild')->with(1)->willReturn(['leader_id' => 1]);
        $repo->method('getActiveMembership')->with(2)->willReturn(null);
        $repo->method('getLastMembership')->with(2)->willReturn([
            'guild_id' => 5,
            'joined_at' => date('Y-m-d H:i:s', time() - 2 * 86400),
            'left_at' => date('Y-m-d H:i:s', time() - 1 * 86400)
        ]);
        $repo->expects($this->never())->method('addMember');

        $this->assertFalse($service->addMember(1, 1, 2));
    }

    public function testAddMemberSucceedsAfterCooldown(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getGuild')->with(1)->willReturn(['leader_id' => 1]);
        $repo->method('getActiveMembership')->with(2)->willReturn(null);
        $repo->method('getLastMembership')->with(2)->willReturn([
            'guild_id' => 5,
            'joined_at' => date('Y-m-d H:i:s', time() - 5 * 86400),
            'left_at' => date('Y-m-d H:i:s', time() - 4 * 86400)
        ]);
        $repo->expects($this->once())->method('addMember')->with(1, 2);

        $this->assertTrue($service->addMember(1, 1, 2));
    }

    public function testTransferLeadershipFailsDuringCooldown(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getGuild')->with(1)->willReturn([
            'leader_id' => 1,
            'last_leader_transfer_at' => date('Y-m-d H:i:s', time() - 3 * 86400)
        ]);
        $repo->method('isMember')->with(1, 2)->willReturn(true);
        $repo->expects($this->never())->method('updateLeader');

        $this->assertFalse($service->transferLeadership(1, 1, 2));
    }

    public function testTransferLeadershipSucceedsAfterCooldown(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getGuild')->with(1)->willReturn([
            'leader_id' => 1,
            'last_leader_transfer_at' => date('Y-m-d H:i:s', time() - 8 * 86400)
        ]);
        $repo->method('isMember')->with(1, 2)->willReturn(true);
        $repo->expects($this->once())->method('updateLeader')->with(1, 2);

        $this->assertTrue($service->transferLeadership(1, 1, 2));
    }
}
