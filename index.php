<?php

declare(strict_types=1);

use App\Controllers\SiteController;
use App\Controllers\QuestionController;
use App\Controllers\FrontController;
use App\Core\Router;
use App\Models\SiteModel;

session_start();

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

$router = new Router();
$siteController = new SiteController(new SiteModel());
$questionController = new QuestionController();
$frontController = new FrontController();

$router->get('', [$siteController, 'home']);
$router->get('home', [$siteController, 'home']);

foreach ($siteController->getLegacyPages() as $legacyRoute => $_legacyFile) {
    $router->get(
        $legacyRoute,
        static function () use ($siteController, $legacyRoute): void {
            $siteController->legacy($legacyRoute);
        }
    );
}

$router->get('about', [$siteController, 'about']);
$router->get('services', [$siteController, 'services']);
$router->get('team', [$frontController, 'afficherMetiers']);
$router->get('contact', [$siteController, 'contact']);
$router->post('contact/submit', [$siteController, 'submitContact']);

$router->get('evaluation', [$questionController, 'evaluation']);
$router->post('evaluation', [$questionController, 'evaluation']);

$router->get('team/quiz', [$frontController, 'afficherQuiz']);
$router->post('team/submit', [$frontController, 'traiterQuiz']);

$router->get('front/quiz', [$frontController, 'afficherQuiz']);
$router->post('front/submit', [$frontController, 'traiterQuiz']);

$router->setNotFound([$siteController, 'notFound']);

$route = trim((string) ($_GET['route'] ?? ''), '/');

if ($route === '') {
    $path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    if ($scriptDir !== '' && str_starts_with($path, $scriptDir)) {
        $path = trim(substr($path, strlen($scriptDir)), '/');
    }

    if ($path === '' || $path === 'index.php') {
        $route = '';
    } else {
        $route = $path;
    }
}

$router->dispatch($route, $_SERVER['REQUEST_METHOD'] ?? 'GET');
