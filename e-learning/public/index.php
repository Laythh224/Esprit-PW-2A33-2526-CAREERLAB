<?php

declare(strict_types=1);

use App\controllers\BackOptionController;
use App\controllers\BackPlanningController;
use App\controllers\BackPlanningOptionController;

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$route = $_GET['r'] ?? 'back/options';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($route) {
    case 'back/options':
        if ($method !== 'GET') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackOptionController())->index();
        break;

    case 'back/options/store':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackOptionController())->store();
        break;

    case 'back/options/update':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackOptionController())->update();
        break;

    case 'back/options/delete':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackOptionController())->delete();
        break;

    case 'back/planning':
        if ($method !== 'GET') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningController())->index();
        break;

    case 'back/planning/store':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningController())->store();
        break;

    case 'back/planning/update':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningController())->update();
        break;

    case 'back/planning/delete':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningController())->delete();
        break;

    case 'back/planning-option':
        if ($method !== 'GET') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningOptionController())->index();
        break;

    case 'back/planning-option/store':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningOptionController())->store();
        break;

    case 'back/planning-option/delete':
        if ($method !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        (new BackPlanningOptionController())->delete();
        break;

    default:
        http_response_code(404);
        echo 'Route introuvable';
        break;
}
