<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\ProfileController;

$auth = new AuthController();
$profile = new ProfileController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$user = $_SESSION['user'] ?? null;

$requireLogin = function () use ($user) {
    if (!$user) {
        header('Location: /login');
        exit;
    }
};

switch ($uri) {
    case '/':
        include __DIR__ . '/../src/views/home.php';
        break;
    case '/login':
        if ($method === 'POST') {
            $auth->login();
        } else {
            $auth->showLoginForm();
        }
        break;
    case '/register':
        if ($method === 'POST') {
            $auth->register();
        } else {
            $auth->showRegisterForm();
        }
        break;
    case '/forgot':
        if ($method === 'POST') {
            $auth->sendResetLink();
        } else {
            $auth->showForgotForm();
        }
        break;
    case '/logout':
        $auth->logout();
        break;
    case '/auctions':
        $requireLogin();
        include __DIR__ . '/../src/views/auctions/index.php';
        break;
    case '/auction-history':
        $requireLogin();
        include __DIR__ . '/../src/views/auctions/history.php';
        break;
    case '/event-history':
        $requireLogin();
        include __DIR__ . '/../src/views/events/history.php';
        break;
    case '/profile':
        $requireLogin();
        if ($method === 'POST') {
            $profile->update();
        } else {
            $profile->show();
        }
        break;
    case '/management':
        $requireLogin();
        if ($user['role'] === 'guild_member') {
            http_response_code(403);
            echo 'Forbidden';
        } else {
            include __DIR__ . '/../src/views/management/index.php';
        }
        break;
    default:
        http_response_code(404);
        echo 'Not Found';
        break;
}
