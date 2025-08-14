<?php
// Main layout for DKP Site views
// Expected variables: \$title, \$content, \$currentPage
// Optionally: \$user for navigation logic

\$title = \$title ?? 'DKP Site';
\$currentPage = \$currentPage ?? '';
\$user = \$user ?? (\$_SESSION['user'] ?? null);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars(\$title) ?></title>
    <style>
        nav a.active {
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav>
    <a href="/" class="<?= \$currentPage === 'home' ? 'active' : '' ?>">Home</a> |
    <a href="/auctions" class="<?= \$currentPage === 'auctions' ? 'active' : '' ?>">Auctions</a> |
    <a href="/auction-history" class="<?= \$currentPage === 'auction-history' ? 'active' : '' ?>">Auction History</a> |
    <a href="/event-history" class="<?= \$currentPage === 'event-history' ? 'active' : '' ?>">Event History</a> |
    <?php if (\$user): ?>
        <a href="/profile" class="<?= \$currentPage === 'profile' ? 'active' : '' ?>">Profile</a>
        <?php if (\$user['role'] !== 'guild_member'): ?> |
            <a href="/management" class="<?= \$currentPage === 'management' ? 'active' : '' ?>">Management</a>
        <?php endif; ?> |
        <a href="/logout">Logout</a>
    <?php else: ?>
        <a href="/login" class="<?= \$currentPage === 'login' ? 'active' : '' ?>">Login</a> |
        <a href="/register" class="<?= \$currentPage === 'register' ? 'active' : '' ?>">Register</a>
    <?php endif; ?>
</nav>
<main>
    <?= \$content ?? '' ?>
</main>
<footer>
    <p>&copy; <?= date('Y') ?> DKP Site</p>
</footer>
</body>
</html>
