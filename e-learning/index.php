<?php

declare(strict_types=1);

use App\Controller\BackOffice\CritereController as BackCritereController;
use App\Controller\BackOffice\FormationController as BackFormationController;
use App\Controller\FrontOffice\FormationController as FrontFormationController;
use App\Controller\FrontOffice\HomeController as FrontHomeController;

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

$route = $_GET['r'] ?? 'back/formations';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$routeAliases = [
    'front/options' => 'front/formations',
    'front/planning' => 'front/formations',
    'front/formation-detail' => 'front/formations',
    'front/certifications' => 'front/formations',
    'back/options' => 'back/formations',
    'back/planning' => 'back/formations',
    'back/planning-option' => 'back/criteres',
];

if (isset($routeAliases[$route])) {
    $route = $routeAliases[$route];
}

$routes = [
    // Front office routes.
    'front' => [FrontHomeController::class, 'index', ['GET']],
    'front/formations' => [FrontFormationController::class, 'index', ['GET']],

    // Back office routes.
    'back/formations' => [BackFormationController::class, 'index', ['GET']],
    'back/formations/store' => [BackFormationController::class, 'store', ['POST']],
    'back/formations/update' => [BackFormationController::class, 'update', ['POST']],
    'back/formations/delete' => [BackFormationController::class, 'delete', ['POST']],
    'back/criteres' => [BackCritereController::class, 'index', ['GET']],
    'back/criteres/store' => [BackCritereController::class, 'store', ['POST']],
    'back/criteres/update' => [BackCritereController::class, 'update', ['POST']],
    'back/criteres/delete' => [BackCritereController::class, 'delete', ['POST']],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    echo 'Route introuvable';
    exit;
}

[$controllerClass, $action, $allowedMethods] = $routes[$route];
if (!in_array($method, $allowedMethods, true)) {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$controller = new $controllerClass();
$controller->{$action}();
