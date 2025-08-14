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
        'GET' => function () use ($requireLogin) {
            $requireLogin();
            include __DIR__ . '/../views/auctions/index.php';
        },
    ],
    '/auction-history' => [
        'GET' => function () use ($requireLogin) {
            $requireLogin();
            include __DIR__ . '/../views/auctions/history.php';
        },
    ],
    '/event-history' => [
        'GET' => function () use ($requireLogin) {
            $requireLogin();
            include __DIR__ . '/../views/events/history.php';
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
        'GET' => function () use ($requireLogin, $user) {
            $requireLogin();
            if ($user['role'] === 'guild_member') {
                http_response_code(403);
                echo 'Forbidden';
            } else {
                include __DIR__ . '/../views/management/index.php';
            }
        },
    ],
];
