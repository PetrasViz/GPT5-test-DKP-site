<?php
$title = 'Management';
$currentPage = 'management';
ob_start();
?>
<h1>Management</h1>

<h2>Auctions</h2>
<table>
    <thead>
        <tr>
            <th><a href="?auction_sort=id&auction_dir=<?= $auctionSort === 'id' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?auction_sort=item&auction_dir=<?= $auctionSort === 'item' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">Item</a></th>
            <th><a href="?auction_sort=status&auction_dir=<?= $auctionSort === 'status' && $auctionDir === 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($auctionItems as $auction): ?>
        <tr>
            <td><?= htmlspecialchars($auction['id']) ?></td>
            <td><?= htmlspecialchars($auction['item']) ?></td>
            <td><?= htmlspecialchars($auction['status']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination">
    <?php if ($auctionPage > 1): ?>
        <a href="?auction_page=<?= $auctionPage-1 ?>&auction_sort=<?= $auctionSort ?>&auction_dir=<?= $auctionDir ?>">Prev</a>
    <?php endif; ?>
    Page <?= $auctionPage ?> of <?= $auctionPages ?>
    <?php if ($auctionPage < $auctionPages): ?>
        <a href="?auction_page=<?= $auctionPage+1 ?>&auction_sort=<?= $auctionSort ?>&auction_dir=<?= $auctionDir ?>">Next</a>
    <?php endif; ?>
</div>

<h2>Events</h2>
<table>
    <thead>
        <tr>
            <th><a href="?event_sort=id&event_dir=<?= $eventSort === 'id' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?event_sort=name&event_dir=<?= $eventSort === 'name' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">Name</a></th>
            <th><a href="?event_sort=date&event_dir=<?= $eventSort === 'date' && $eventDir === 'asc' ? 'desc' : 'asc' ?>">Date</a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($eventItems as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['id']) ?></td>
            <td><?= htmlspecialchars($event['name']) ?></td>
            <td><?= htmlspecialchars($event['date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination">
    <?php if ($eventPage > 1): ?>
        <a href="?event_page=<?= $eventPage-1 ?>&event_sort=<?= $eventSort ?>&event_dir=<?= $eventDir ?>">Prev</a>
    <?php endif; ?>
    Page <?= $eventPage ?> of <?= $eventPages ?>
    <?php if ($eventPage < $eventPages): ?>
        <a href="?event_page=<?= $eventPage+1 ?>&event_sort=<?= $eventSort ?>&event_dir=<?= $eventDir ?>">Next</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
