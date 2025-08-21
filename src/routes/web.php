<?php

use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\AuctionController;
use App\Controllers\EventController;
use App\Controllers\ManagementController;
use App\Controllers\GuildController;

// Ensure session is started so that route file can be included independently
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instantiate controllers if they haven't been provided by the caller
$auth = $auth ?? new AuthController();
$profile = $profile ?? new ProfileController();
$auction = $auction ?? new AuctionController();
$event = $event ?? new EventController();
$management = $management ?? new ManagementController();
$guild = $guild ?? new GuildController();

// Retrieve the current user and login helper if not already defined
$user = $user ?? ($_SESSION['user'] ?? null);
$requireLogin = $requireLogin ?? function () use ($user) {
    if (!$user) {
        header('Location: /login');
        exit;
    }
};

return [
    '/' => [
        'GET' => function () {
            include __DIR__ . '/../views/home.php';
        },
    ],
    '/login' => [
        'GET' => [$auth, 'showLoginForm'],
        'POST' => [$auth, 'login'],
    ],
    '/register' => [
        'GET' => [$auth, 'showRegisterForm'],
        'POST' => [$auth, 'register'],
    ],
    '/forgot' => [
        'GET' => [$auth, 'showForgotForm'],
        'POST' => [$auth, 'sendResetLink'],
    ],
    '/logout' => [
        'GET' => [$auth, 'logout'],
    ],
    '/guild/register' => [
        'GET' => function () use ($requireLogin, $guild) {
            $requireLogin();
            $guild->showRegisterForm();
        },
        'POST' => function () use ($requireLogin, $guild) {
            $requireLogin();
            $guild->register();
        },
    ],
    '/guilds' => [
        'GET' => function () use ($requireLogin, $guild, $user) {
            $requireLogin();
            if ($user['role'] !== 'ADMIN') {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $guild->index();
            }
        },
    ],
    '/auctions' => [
        'GET' => function () use ($requireLogin, $auction) {
            $requireLogin();
            $auction->index();
        },
    ],
    '/auction-history' => [
        'GET' => function () use ($requireLogin) {
            $requireLogin();
            include __DIR__ . '/../views/auctions/history.php';
        },
    ],
    '/event-history' => [
        'GET' => function () use ($requireLogin, $event) {
            $requireLogin();
            $event->history();
        },
    ],
    '/profile' => [
        'GET' => function () use ($requireLogin, $profile) {
            $requireLogin();
            $profile->show();
        },
        'POST' => function () use ($requireLogin, $profile) {
            $requireLogin();
            $profile->update();
        },
    ],
    '/profile/join' => [
        'POST' => function () use ($requireLogin, $profile) {
            $requireLogin();
            $profile->joinGuild();
        },
    ],
    '/profile/leave' => [
        'POST' => function () use ($requireLogin, $profile) {
            $requireLogin();
            $profile->leaveGuild();
        },
    ],
    '/management' => [
        'GET' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->index();
            }
        },
    ],
    '/management/motd' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if ($user['role'] === 'MEMBER') {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->updateMotd();
            }
        },
    ],
    '/management/event-add' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->addEvent();
            }
        },
    ],
    '/management/event-delete' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->deleteEvent();
            }
        },
    ],
    '/management/auction-settings' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->updateAuctionSettings();
            }
        },
    ],
    '/management/auction-close' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->closeAuction();
            }
        },
    ],
    '/management/auction-delete' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->deleteAuction();
            }
        },
    ],
    '/management/auction-winner' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->setAuctionWinner();
            }
        },
    ],
    '/management/auction-minbid' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->setAuctionMinBid();
            }
        },
    ],
    '/management/auction-time' => [
        'POST' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if (!in_array($user['role'], ['ADMIN', 'LEADER', 'ADVISOR'])) {
                http_response_code(403);
                $message = 'Forbidden';
                include __DIR__ . '/../views/errors/error.php';
            } else {
                $management->setAuctionTime();
            }
        },
    ],
];
