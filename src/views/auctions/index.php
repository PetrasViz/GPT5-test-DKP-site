<?php
$title = 'Auctions';
$currentPage = 'auctions';
ob_start();
?>
<h1>Auctions</h1>
<table>
    <thead>
        <tr>
            <th><a href="?sort=id&dir=<?= $sort === 'id' && $dir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?sort=item&dir=<?= $sort === 'item' && $dir === 'asc' ? 'desc' : 'asc' ?>">Item</a></th>
            <th><a href="?sort=status&dir=<?= $sort === 'status' && $dir === 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
            <th><a href="?sort=created_at&dir=<?= $sort === 'created_at' && $dir === 'asc' ? 'desc' : 'asc' ?>">Created</a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $auction): ?>
        <tr>
            <td><?= htmlspecialchars($auction['id']) ?></td>
            <td><?= htmlspecialchars($auction['item']) ?></td>
            <td><?= htmlspecialchars($auction['status']) ?></td>
            <td><?= htmlspecialchars($auction['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&sort=<?= $sort ?>&dir=<?= $dir ?>">Prev</a>
    <?php endif; ?>
    Page <?= $page ?> of <?= $pages ?>
    <?php if ($page < $pages): ?>
        <a href="?page=<?= $page+1 ?>&sort=<?= $sort ?>&dir=<?= $dir ?>">Next</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
