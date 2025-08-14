<?php
$title = 'Event History';
$currentPage = 'event-history';
ob_start();
?>
<h1>Event History</h1>
<table>
    <thead>
        <tr>
            <th><a href="?sort=id&dir=<?= $sort === 'id' && $dir === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?sort=name&dir=<?= $sort === 'name' && $dir === 'asc' ? 'desc' : 'asc' ?>">Name</a></th>
            <th><a href="?sort=date&dir=<?= $sort === 'date' && $dir === 'asc' ? 'desc' : 'asc' ?>">Date</a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['id']) ?></td>
            <td><?= htmlspecialchars($event['name']) ?></td>
            <td><?= htmlspecialchars($event['date']) ?></td>
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
