<?php
// Main layout for DKP Site views
// Expected variables: $title, $content, $currentPage
// Optionally: $user for navigation logic

$title = $title ?? 'DKP Site';
$currentPage = $currentPage ?? '';
$user = $user ?? ($_SESSION['user'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="site-body">
<nav class="navbar navbar-expand-lg navbar-dark site-header" aria-label="Primary navigation">
    <div class="container-fluid">
        <a class="navbar-brand site-logo" href="/">DKP Site</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'auctions' ? 'active' : '' ?>" href="/auctions">Auctions</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'auction-history' ? 'active' : '' ?>" href="/auction-history">Auction History</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'event-history' ? 'active' : '' ?>" href="/event-history">Event History</a></li>
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>" href="/profile">Profile</a></li>
                    <?php if ($user['role'] !== 'guild_member'): ?>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'management' ? 'active' : '' ?>" href="/management">Management</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?= $currentPage === 'login' ? 'active' : '' ?>" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link <?= $currentPage === 'register' ? 'active' : '' ?>" href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="site-main container">
    <?= $content ?? '' ?>
</main>
<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> DKP Site</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
