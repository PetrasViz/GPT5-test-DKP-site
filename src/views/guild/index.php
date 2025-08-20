<?php
$title = 'Guilds';
$currentPage = 'guilds';
ob_start();
?>
<h1>Guilds</h1>
<table>
    <tr><th>ID</th><th>Name</th><th>Leader</th></tr>
    <?php foreach ($guilds as $g): ?>
        <tr>
            <td><?= htmlspecialchars($g['id']) ?></td>
            <td><?= htmlspecialchars($g['name']) ?></td>
            <td><?= htmlspecialchars($g['leader_id']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
