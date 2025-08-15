<?php
namespace App\Services;

use App\Repositories\GuildRepository;

class GuildService
{
    private GuildRepository $guilds;

    public function __construct(?GuildRepository $guilds = null)
    {
        $this->guilds = $guilds ?? new GuildRepository();
    }

    public function registerGuild(int $leaderId, string $name): int
    {
        return $this->guilds->createGuild($name, $leaderId);
    }

    public function addMember(int $leaderId, int $guildId, int $userId): bool
    {
        $guild = $this->guilds->getGuild($guildId);
        if (!$guild || (int)$guild['leader_id'] !== $leaderId) {
            return false;
        }
        $active = $this->guilds->getActiveMembership($userId);
        if ($active) {
            return false;
        }
        $last = $this->guilds->getLastMembership($userId);
        if ($last && strtotime($last['joined_at']) > time() - 3 * 24 * 60 * 60) {
            return false;
        }
        $this->guilds->addMember($guildId, $userId);
        return true;
    }

    public function transferLeadership(int $guildId, int $currentLeaderId, int $newLeaderId): bool
    {
        $guild = $this->guilds->getGuild($guildId);
        if (!$guild || (int)$guild['leader_id'] !== $currentLeaderId) {
            return false;
        }
        $last = $guild['last_leader_transfer_at'];
        if ($last && strtotime($last) > time() - 7 * 24 * 60 * 60) {
            return false;
        }
        if (!$this->guilds->isMember($guildId, $newLeaderId)) {
            return false;
        }
        $this->guilds->updateLeader($guildId, $newLeaderId);
        return true;
    }
}
