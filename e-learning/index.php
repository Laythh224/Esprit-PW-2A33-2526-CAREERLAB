<?php

declare(strict_types=1);

use App\Controller\BackOffice\ClientController as BackClientController;
use App\Controller\BackOffice\FormationController as BackFormationController;
use App\Controller\BackOffice\SessionController as BackSessionController;
use App\Controller\FrontOffice\CertificateController as FrontCertificateController;
use App\Controller\FrontOffice\FormationController as FrontFormationController;
use App\Controller\FrontOffice\HomeController as FrontHomeController;
use App\Controller\FrontOffice\InscriptionController as FrontInscriptionController;

session_start();

$scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$eLearningWebBase = $scriptName !== '' ? rtrim(dirname($scriptName), '/') : '';
if ($eLearningWebBase === '') {
    $eLearningWebBase = '/e-learning';
}
define('E_LEARNING_WEB_BASE', $eLearningWebBase);

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
    'front/certifications' => 'front/certificate',
    'back/options' => 'back/formations',
    'back/planning' => 'back/formations',
    'back/planning-option' => 'back/sessions',
    'back/criteres' => 'back/sessions',
    'back/criteres/store' => 'back/sessions/store',
    'back/criteres/update' => 'back/sessions/update',
    'back/criteres/delete' => 'back/sessions/delete',
];

if (isset($routeAliases[$route])) {
    $route = $routeAliases[$route];
}

$routes = [
    // Front office routes.
    'front' => [FrontHomeController::class, 'index', ['GET']],
    'front/certificate' => [FrontCertificateController::class, 'show', ['GET']],
    'front/formations' => [FrontFormationController::class, 'index', ['GET']],
    'front/inscription' => [FrontInscriptionController::class, 'form', ['GET']],
    'front/inscription/submit' => [FrontInscriptionController::class, 'submit', ['POST']],

    // Back office routes.
    'back/formations' => [BackFormationController::class, 'index', ['GET']],
    'back/formations/store' => [BackFormationController::class, 'store', ['POST']],
    'back/formations/update' => [BackFormationController::class, 'update', ['POST']],
    'back/formations/delete' => [BackFormationController::class, 'delete', ['POST']],
    'back/sessions' => [BackSessionController::class, 'index', ['GET']],
    'back/sessions/store' => [BackSessionController::class, 'store', ['POST']],
    'back/sessions/update' => [BackSessionController::class, 'update', ['POST']],
    'back/sessions/delete' => [BackSessionController::class, 'delete', ['POST']],
    'back/clients' => [BackClientController::class, 'index', ['GET']],
    'back/clients/delete' => [BackClientController::class, 'delete', ['POST']],
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
