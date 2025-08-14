<!DOCTYPE html>
<html>
<head>
    <title>DKP Home</title>
</head>
<body>
<?php if (!empty($_SESSION['user'])): $u = $_SESSION['user']; ?>
    <h1>Welcome, <?= htmlspecialchars($u['display_name']) ?></h1>
    <p>This site tracks guild activity points and loot auctions.</p>
    <nav>
        <a href="/">Home</a> |
        <a href="/auctions">Auctions</a> |
        <a href="/auction-history">Auction History</a> |
        <a href="/event-history">Event History</a> |
        <a href="/profile">Profile</a>
        <?php if ($u['role'] !== 'guild_member'): ?> |
            <a href="/management">Management</a>
        <?php endif; ?> |
        <a href="/logout">Logout</a>
    </nav>
    <p>Use the navigation links above to manage your guild.</p>
<?php else: ?>
    <h1>DKP Site</h1>
    <p>Please <a href="/login">login</a> or <a href="/register">register</a>.</p>
<?php endif; ?>
</body>
</html>
