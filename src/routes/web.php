<?php

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
    '/management' => [
        'GET' => function () use ($requireLogin, $user, $management) {
            $requireLogin();
            if ($user['role'] === 'guild_member') {
                http_response_code(403);
                echo 'Forbidden';
            } else {
                $management->index();
            }
        },
    ],
];
