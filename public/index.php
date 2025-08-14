<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\AuctionController;
use App\Controllers\EventController;
use App\Controllers\ManagementController;

$auth = new AuthController();
$profile = new ProfileController();
$auction = new AuctionController();
$event = new EventController();
$management = new ManagementController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$user = $_SESSION['user'] ?? null;

$requireLogin = function () use ($user) {
    if (!$user) {
        header('Location: /login');
        exit;
    }
};

$routes = require __DIR__ . '/../src/routes/web.php';

if (isset($routes[$uri][$method])) {
    $action = $routes[$uri][$method];
    $action();
} else {
    http_response_code(404);
    echo 'Not Found';
}
