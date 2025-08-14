<?php
$u = $_SESSION['user'] ?? null;
$title = 'DKP Home';
$currentPage = 'home';
ob_start();
?>
<?php if (!empty($u)): ?>
    <h1>Welcome, <?= htmlspecialchars($u['display_name']) ?></h1>
    <p>This site tracks guild activity points and loot auctions.</p>
    <p>Use the navigation links above to manage your guild.</p>
<?php else: ?>
    <h1>DKP Site</h1>
    <p>Please <a href="/login">login</a> or <a href="/register">register</a>.</p>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
