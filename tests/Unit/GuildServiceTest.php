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

        $repo->expects($this->once())->method('getActiveMembership')->with(1)->willReturn(null);
        $repo->expects($this->never())->method('leaveGuild');
        $repo->expects($this->once())
            ->method('createGuild')
            ->with('Guild One', 1)
            ->willReturn(10);

        $this->assertSame(10, $service->registerGuild(1, 'Guild One'));
    }

    public function testRegisterGuildLeavesExistingGuild(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->expects($this->once())->method('getActiveMembership')->with(1)->willReturn(['guild_id' => 2]);
        $repo->expects($this->once())->method('leaveGuild')->with(1);
        $repo->expects($this->once())->method('createGuild')->with('Guild Two', 1)->willReturn(11);

        $this->assertSame(11, $service->registerGuild(1, 'Guild Two'));
    }

    public function testJoinGuildLeavesExistingGuild(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getActiveMembership')->with(5)->willReturn(['guild_id' => 2]);
        $repo->expects($this->once())->method('leaveGuild')->with(5);
        $repo->expects($this->once())->method('addMember')->with(3, 5);

        $this->assertTrue($service->joinGuild(5, 3));
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

    public function testSetMotdUpdatesRepository(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->expects($this->once())->method('updateMotd')->with(3, 'Be brave');

        $service->setMotd(3, 'Be brave');
    }

    public function testGetActiveMembershipHandlesDatabaseExceptions(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getActiveMembership')
            ->willThrowException(new \PDOException('DB error'));

        $this->assertNull($service->getActiveMembership(1));
    }

    public function testGetGuildHandlesDatabaseExceptions(): void
    {
        $repo = $this->createMock(GuildRepository::class);
        $service = new GuildService($repo);

        $repo->method('getGuild')
            ->willThrowException(new \PDOException('DB error'));

        $this->assertNull($service->getGuild(1));
    }
}
