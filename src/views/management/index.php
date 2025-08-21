<?php
$title = 'Management';
$currentPage = 'management';

$navItems = [
    ['id' => 'motd', 'label' => 'Message', 'icon' => 'bi-megaphone'],
    ['id' => 'events', 'label' => 'Events', 'icon' => 'bi-calendar-event'],
    ['id' => 'auctions', 'label' => 'Auctions', 'icon' => 'bi-hammer'],
];
if (in_array($user['role'], ['LEADER', 'ADMIN'])) {
    $navItems[] = ['id' => 'members', 'label' => 'Members', 'icon' => 'bi-people'];
}
if ($user['role'] === 'ADMIN') {
    $navItems[] = ['id' => 'guilds', 'label' => 'Guilds', 'icon' => 'bi-list-check'];
}
$allowedSections = array_column($navItems, 'id');
$section = in_array($section, $allowedSections) ? $section : $allowedSections[0];

ob_start();
?>
<div class="management-container">
    <aside class="management-sidebar">
        <button id="managementToggle" class="toggle"><i class="bi bi-chevron-left"></i></button>
        <ul class="management-nav">
            <?php foreach ($navItems as $item): ?>
                <li>
                    <a href="/management?section=<?= $item['id'] ?>" class="<?= $section === $item['id'] ? 'active' : '' ?>">
                        <i class="bi <?= $item['icon'] ?>"></i>
                        <span class="text"><?= htmlspecialchars($item['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
    <section class="management-main">
        <?php if ($section === 'motd'): ?>
            <?php if (!empty($currentGuild)): ?>
                <h2>Message of the Day</h2>
                <form method="post" action="/management/motd" id="motdForm">
                    <?= \App\Helpers\Csrf::inputField() ?>
                    <textarea name="motd" id="motd" rows="3" cols="50"><?= htmlspecialchars($currentGuild['motd'] ?? '') ?></textarea><br>
                    <button type="submit">Save</button>
                </form>
            <?php else: ?>
                <p>No guild available.</p>
            <?php endif; ?>

        <?php elseif ($section === 'guilds' && $user['role'] === 'ADMIN'): ?>
            <h2>Guilds</h2>
            <table>
                <tr><th>ID</th><th>Name</th><th>Leader</th><th>Actions</th></tr>
                <?php foreach ($allGuilds as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['id']) ?></td>
                    <td><?= htmlspecialchars($g['name']) ?></td>
                    <td><?= htmlspecialchars($g['leader_id']) ?></td>
                    <td>
                        <form method="post" action="/management/guild-block" style="display:inline">
                            <?= \App\Helpers\Csrf::inputField() ?>
                            <input type="hidden" name="id" value="<?= $g['id'] ?>">
                            <button type="submit">Block</button>
                        </form>
                        <form method="post" action="/management/guild-ban" style="display:inline">
                            <?= \App\Helpers\Csrf::inputField() ?>
                            <input type="hidden" name="id" value="<?= $g['id'] ?>">
                            <button type="submit">Ban</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        <?php elseif ($section === 'auctions'): ?>
            <h2>Auction Settings</h2>
            <form method="post" action="/management/auction-settings">
                <?= \App\Helpers\Csrf::inputField() ?>
                <label>Default Min Bid: <input type="number" name="default_min_bid" value="<?= htmlspecialchars($defaultMinBid) ?>"></label>
                <label>Default Duration (minutes): <input type="number" name="default_auction_time" value="<?= htmlspecialchars($defaultAuctionTime) ?>"></label>
                <button type="submit">Save</button>
            </form>

            <h2>Auctions</h2>
            <table>
                <thead>
                    <tr>
                        <th><a href="?section=auctions&auction_sort=id&auction_dir=<?= $auctionSort === 'id' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
                        <th><a href="?section=auctions&auction_sort=item&auction_dir=<?= $auctionSort === 'item' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">Item</a></th>
                        <th><a href="?section=auctions&auction_sort=status&auction_dir=<?= $auctionSort === 'status' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
                        <th>Min Bid</th>
                        <th>Ends</th>
                        <th>Winner</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auctionItems as $auction): ?>
                    <tr>
                        <td><?= htmlspecialchars($auction['id']) ?></td>
                        <td><?= htmlspecialchars($auction['item']) ?></td>
                        <td><?= htmlspecialchars($auction['status']) ?></td>
                        <td><?= htmlspecialchars($auction['min_bid']) ?></td>
                        <td><?= date('Y-m-d H:i', $auction['end_time']) ?></td>
                        <td><?= htmlspecialchars($auction['winner'] ?? '') ?></td>
                        <td>
                            <form method="post" action="/management/auction-close" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $auction['id'] ?>">
                                <button type="submit">Close</button>
                            </form>
                            <form method="post" action="/management/auction-delete" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $auction['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                            <form method="post" action="/management/auction-winner" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $auction['id'] ?>">
                                <input type="text" name="winner" placeholder="Winner" value="<?= htmlspecialchars($auction['winner'] ?? '') ?>">
                                <button type="submit">Set</button>
                            </form>
                            <form method="post" action="/management/auction-minbid" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $auction['id'] ?>">
                                <input type="number" name="min_bid" value="<?= htmlspecialchars($auction['min_bid']) ?>">
                                <button type="submit">Min Bid</button>
                            </form>
                            <form method="post" action="/management/auction-time" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $auction['id'] ?>">
                                <input type="datetime-local" name="end_time" value="<?= date('Y-m-d\TH:i', $auction['end_time']) ?>">
                                <button type="submit">Time</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php if ($auctionPage > 1): ?>
                    <a href="?section=auctions&auction_page=<?= $auctionPage-1 ?>&auction_sort=<?= $auctionSort ?>&auction_dir=<?= $auctionDir ?>">Prev</a>
                <?php endif; ?>
                Page <?= $auctionPage ?> of <?= $auctionPages ?>
                <?php if ($auctionPage < $auctionPages): ?>
                    <a href="?section=auctions&auction_page=<?= $auctionPage+1 ?>&auction_sort=<?= $auctionSort ?>&auction_dir=<?= $auctionDir ?>">Next</a>
                <?php endif; ?>
            </div>

        <?php elseif ($section === 'events'): ?>
            <h2>Events</h2>
            <h3>Add Event</h3>
            <form method="post" action="/management/event-add">
                <?= \App\Helpers\Csrf::inputField() ?>
                <input type="text" name="name" placeholder="Name">
                <input type="date" name="date" value="<?= date('Y-m-d') ?>">
                <input type="text" name="loot" placeholder="Loot items comma separated">
                <button type="submit">Add</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th><a href="?section=events&event_sort=id&event_dir=<?= $eventSort === 'id' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
                        <th><a href="?section=events&event_sort=name&event_dir=<?= $eventSort === 'name' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">Name</a></th>
                        <th><a href="?section=events&event_sort=date&event_dir=<?= $eventSort === 'date' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">Date</a></th>
                        <th>Loot</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventItems as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['id']) ?></td>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= htmlspecialchars($event['date']) ?></td>
                        <td><?= htmlspecialchars(implode(', ', $event['loot'] ?? [])) ?></td>
                        <td>
                            <form method="post" action="/management/event-delete" style="display:inline">
                                <?= \App\Helpers\Csrf::inputField() ?>
                                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php if ($eventPage > 1): ?>
                    <a href="?section=events&event_page=<?= $eventPage-1 ?>&event_sort=<?= $eventSort ?>&event_dir=<?= $eventDir ?>">Prev</a>
                <?php endif; ?>
                Page <?= $eventPage ?> of <?= $eventPages ?>
                <?php if ($eventPage < $eventPages): ?>
                    <a href="?section=events&event_page=<?= $eventPage+1 ?>&event_sort=<?= $eventSort ?>&event_dir=<?= $eventDir ?>">Next</a>
                <?php endif; ?>
            </div>

        <?php elseif ($section === 'members'): ?>
            <h2>Guild Members</h2>
            <table>
                <tr><th>Name</th><th>Role</th><th>Joined</th></tr>
                <?php foreach ($guildMembers as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['display_name']) ?></td>
                    <td><?= htmlspecialchars($m['role']) ?></td>
                    <td><?= htmlspecialchars($m['joined_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
</div>
<script src="/js/management.js"></script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

