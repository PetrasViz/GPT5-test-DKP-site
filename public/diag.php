<?php
// Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "Diag start\n\n";

// Resolve paths relative to /public
$base = __DIR__ . '/..'; // this should be /home/.../public_html
$vendor = $base . '/vendor/autoload.php';
$routes = $base . '/src/routes/web.php';

echo "Expected base: $base\n";
echo "Check vendor: $vendor => " . (file_exists($vendor) ? "OK\n" : "MISSING\n");
echo "Check routes: $routes => " . (file_exists($routes) ? "OK\n" : "MISSING\n");

// Try requiring autoload safely
if (file_exists($vendor)) {
    echo "Including autoload...\n";
    require_once $vendor;
    echo "Autoload included OK\n";
} else {
    echo "ERROR: vendor/autoload.php not found.\n";
    echo "FIX: Run 'composer install' in the project root (one level above /public), or upload the /vendor folder.\n";
    exit;
}

// Try requiring routes
if (file_exists($routes)) {
    echo "Including routes...\n";

    // Define stubs for variables expected by the routes file to avoid warnings
    $auth = $guild = $auction = $event = $profile = $management = new class {};
    $requireLogin = function () {};
    $user = ['role' => ''];


    $routesArr = require $routes;
    echo "Routes loaded: " . (is_array($routesArr) ? count($routesArr) . " entries\n" : "unexpected type\n");
} else {
    echo "ERROR: src/routes/web.php not found at expected path.\n";
    echo "Check your directory structure and the relative path in index.php\n";
    exit;
}

echo "\nDiag done. If this page works, the 500 is likely inside one of your controllers.\n";
?>
