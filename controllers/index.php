<?php
session_start();

spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/../models/' . $className . '.php',
        __DIR__ . '/../Models/' . $className . '.php',
        __DIR__ . '/' . $className . '.php',
        __DIR__ . '/../controllers/' . $className . '.php',
        __DIR__ . '/../Controllers/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$routeAliases = [
    'main' => 'accueil',
    'home' => 'accueil',
    'signup' => 'creer-compte',
    'register' => 'creer-compte',
    'admin' => 'dashboard-admin',
    'pdf' => 'pdf',
    'profile' => 'profile',
    'login' => 'login',
    'logout' => 'logout',
    'dashboard' => 'dashboard',
];

$adminAliasController = new AdminController();

$routeR = isset($_GET['r']) ? strtolower(trim((string) $_GET['r'])) : '';
$page = isset($_GET['page']) ? (string) $_GET['page'] : '';

if ($page === '' && $routeR !== '') {
    $page = $routeAliases[$routeR] ?? $routeR;

    if ($routeR === 'admin' && isset($_GET['view'])) {
        $page = $adminAliasController->resolveAdminPage((string) $_GET['view']);
    }
}

if ($page === '') {
    $page = 'accueil';
}

if ($page === 'pdf') {
    (new PdfController())->generate($_POST ?? []);
}

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
    'admin' => __DIR__ . '/../views/admin.php',
    'dashboard-admin' => __DIR__ . '/../views/index.view.php',
    'gestion-utilisateurs' => __DIR__ . '/../views/components/utilisateur.php',
    'gestion-formateurs' => __DIR__ . '/../views/components/formateur.php',
    'gestion-entreprises' => __DIR__ . '/../views/components/entreprise.php',
    'profile' => __DIR__ . '/../views/profile.view.php',
];

$homeRoutes = [
    'accueil' => 'index',
    'main' => 'index',
    'creer-compte' => 'creerCompte',
];

try {
    $database = new Database();
    $conn = $database->connection();

    $cvUploadService = new CvUploadService();
    $userModel = new UserModel($conn, $cvUploadService);
    $formateurModel = new FormateurModel($conn, $cvUploadService);
    $entrepriseModel = new EntrepriseModel($conn);

    $homeController = new HomeController();
    $authController = new AuthController($userModel, $formateurModel, $entrepriseModel);
    $dashboardController = new DashboardController($userModel, $formateurModel, $entrepriseModel);
    $userController = new UserController($userModel);
    $formateurController = new FormateurController($formateurModel);
    $entrepriseController = new EntrepriseController($entrepriseModel);

    $controllerRoutes = [
        'login' => [$authController, 'login'],
        'logout' => [$authController, 'logout'],
        'dashboard' => [$dashboardController, 'index'],
        'utilisateur' => [$userController, 'signup'],
        'formateur' => [$formateurController, 'signup'],
        'entreprise' => [$entrepriseController, 'signup'],
        'api-utilisateurs' => [$userController, 'api'],
        'api-formateurs' => [$formateurController, 'api'],
        'api-entreprises' => [$entrepriseController, 'api'],
    ];

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $page)) {
        $page = '404';
    }

    $mainController = new MainController($homeController, $homeRoutes, $controllerRoutes, $viewRoutes);
    $handled = $mainController->handle($page);

    if (!$handled) {
        if ($isApiRoute) {
            $renderJsonError(404, 'Ressource introuvable');
        } else {
            $renderHtmlError(404, 'Erreur 404 - Page introuvable', "La page n'existe pas ou a ete deplacee.");
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
