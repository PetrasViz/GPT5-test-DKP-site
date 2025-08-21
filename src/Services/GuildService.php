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
        $active = $this->guilds->getActiveMembership($leaderId);
        if ($active) {
            $this->guilds->leaveGuild($leaderId);
        }
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

    public function leaveGuild(int $userId): void
    {
        $this->guilds->leaveGuild($userId);
    }

    public function joinGuild(int $userId, int $guildId): bool
    {
        $active = $this->guilds->getActiveMembership($userId);
        if ($active) {
            $this->guilds->leaveGuild($userId);
        }
        $this->guilds->addMember($guildId, $userId);
        return true;
    }

    public function getActiveMembership(int $userId): ?array
    {
        try {
            return $this->guilds->getActiveMembership($userId);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function getGuild(int $id): ?array
    {
        try {
            return $this->guilds->getGuild($id);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function setMotd(int $guildId, string $motd): void
    {
        $this->guilds->updateMotd($guildId, $motd);
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

    /**
     * Return all guilds for administrative review.
     */
    public function listGuilds(): array
    {
        return $this->guilds->getAllGuilds();
    }

    /**
     * Return active members of a guild.
     *
     * @param int $guildId
     * @return array<int, array<string, mixed>>
     */
    public function listMembers(int $guildId): array
    {
        try {
            return $this->guilds->getGuildMembers($guildId);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Allow an administrator to join a guild either as a regular member or as
     * the leader. This bypasses the normal leader and cooldown checks.
     */
    public function adminJoinGuild(int $guildId, int $userId, bool $asLeader = false): bool
    {
        $active = $this->guilds->getActiveMembership($userId);
        if ($active) {
            return false;
        }
        $this->guilds->addMember($guildId, $userId);
        if ($asLeader) {
            $this->guilds->updateLeader($guildId, $userId);
        }
        return true;
    }

    /**
     * Block a guild from performing operations.
     */
    public function blockGuild(int $guildId): void
    {
        $this->guilds->blockGuild($guildId);
    }

    /**
     * Ban a guild.
     */
    public function banGuild(int $guildId): void
    {
        $this->guilds->banGuild($guildId);
    }

    /**
     * Remove a guild entirely.
     */
    public function removeGuild(int $guildId): void
    {
        $this->guilds->deleteGuild($guildId);
    }

    /**
     * Change the leader of a guild immediately.
     */
    public function changeLeader(int $guildId, int $newLeaderId): void
    {
        $this->guilds->updateLeader($guildId, $newLeaderId);
    }
}
