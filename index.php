<?php
session_start();

spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/models/' . $className . '.php',
        __DIR__ . '/controllers/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$page = $_GET['page'] ?? 'accueil';
$isApiRoute = strpos($page, 'api-') === 0;

$renderHtmlError = static function (int $statusCode, string $title, string $message): void {
    if (!headers_sent()) {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
    }

    echo '<h1>' . htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</h1>';
    echo '<p>' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
};
$renderJsonError = static function (int $statusCode, string $error, ?string $details = null): void {
    if (!headers_sent()) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
    }

    $payload = [
        'ok' => false,
        'error' => $error,
    ];

    if ($details !== null) {
        $payload['details'] = $details;
    }

    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
};

$viewRoutes = [
    'dashboard-admin' => __DIR__ . '/views/index.view.php',
    'gestion-utilisateurs' => __DIR__ . '/views/components/utilisateur.php',
    'gestion-formateurs' => __DIR__ . '/views/components/formateur.php',
    'gestion-entreprises' => __DIR__ . '/views/components/entreprise.php',
    'profile' => __DIR__ . '/views/profile.view.php',
];

$homeRoutes = [
    'accueil' => 'index',
    'creer-compte' => 'creerCompte',
];

$containerRoutes = [
    'login' => ['factory' => 'makeAuthController', 'action' => 'login'],
    'logout' => ['factory' => 'makeAuthController', 'action' => 'logout'],
    'dashboard' => ['factory' => 'makeDashboardController', 'action' => 'index'],
    'utilisateur' => ['factory' => 'makeUserSignupController', 'action' => 'index'],
    'entreprise' => ['factory' => 'makeEntrepriseSignupController', 'action' => 'index'],
    'formateur' => ['factory' => 'makeFormateurSignupController', 'action' => 'index'],
    'api-entreprises' => ['factory' => 'makeEntrepriseApiController', 'action' => 'handle'],
    'api-formateurs' => ['factory' => 'makeFormateurApiController', 'action' => 'handle'],
    'api-utilisateurs' => ['factory' => 'makeUserApiController', 'action' => 'handle'],
];

try {
    $database = new Database();
    $conn = $database->connection();
    $container = new AppContainer($conn);

    // Sécurité : Validation basique
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $page)) {
        $page = '404';
    }

    $handled = false;

    if (isset($homeRoutes[$page])) {
        $action = $homeRoutes[$page];
        $controller = new HomeController();
        $controller->$action();
        $handled = true;
    } elseif (isset($containerRoutes[$page])) {
        $route = $containerRoutes[$page];
        $factory = $route['factory'];
        $action = $route['action'];

        $controller = $container->$factory();
        $controller->$action();
        $handled = true;
    } elseif (isset($viewRoutes[$page])) {
        require_once $viewRoutes[$page];
        $handled = true;
    }

    if (!$handled) {
        if ($isApiRoute) {
            $renderJsonError(404, 'Ressource introuvable');
        } else {
            $renderHtmlError(404, 'Erreur 404 - Page introuvable', "La page n'existe pas ou a été déplacée.");
        }
    }
} catch (Throwable $exception) {
    $statusCode = 500;

    error_log('[mon_site] Unhandled exception: ' . $exception->getMessage());

    if ($isApiRoute) {
        $renderJsonError($statusCode, 'Erreur serveur', ini_get('display_errors') ? $exception->getMessage() : null);
    } elseif (ini_get('display_errors')) {
        $renderHtmlError($statusCode, 'Erreur serveur', $exception->getMessage());
    } else {
        $renderHtmlError($statusCode, 'Erreur serveur', 'Une erreur inattendue est survenue. Veuillez reessayer plus tard.');
    }
}
