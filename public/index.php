<?php
session_start();

require_once __DIR__ . '/../src/controllers/AuthController.php';
$controller = new AuthController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/login':
        if ($method === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case '/register':
        if ($method === 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;
    case '/forgot':
        if ($method === 'POST') {
            $controller->sendResetLink();
        } else {
            $controller->showForgotForm();
        }
        break;
    default:
        echo 'Home page';
        break;
}
