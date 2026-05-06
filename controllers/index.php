<?php
session_start();

$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

require_once __DIR__ . '/../models/Metier.php';
require_once __DIR__ . '/MetiersController.php';

spl_autoload_register(function (string $className): void {
    $paths = [
        __DIR__ . '/../Models/' . $className . '.php',
        __DIR__ . '/' . $className . '.php',
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

    require __DIR__ . '/../Views/error.view.php';
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
    'admin' => __DIR__ . '/../Views/admin.php',
    'gestion-utilisateurs' => __DIR__ . '/../Views/components/utilisateur.php',
    'gestion-formateurs' => __DIR__ . '/../Views/components/formateur.php',
    'gestion-entreprises' => __DIR__ . '/../Views/components/entreprise.php',
];

$homeRoutes = [
    'accueil' => 'index',
    'main' => 'index',
    'contact' => 'index',
    'creer-compte' => 'creerCompte',
];

try {
    $database = new Database();
    $conn = $database->connection();

    $cvUploadService = new CvService();
    $signupValidator = new SignupValidator($conn, null, $cvUploadService);
    $userModel = new UserModel($conn, $cvUploadService, $signupValidator);
    $formateurModel = new FormateurModel($conn, $cvUploadService, $signupValidator);
    $entrepriseModel = new EntrepriseModel($conn, $signupValidator);

    $homeController = new HomeController();
    $metiersController = new MetiersController($conn);
    $authController = new AuthController($userModel, $formateurModel, $entrepriseModel);
    $dashboardController = new DashboardController($userModel, $formateurModel, $entrepriseModel);

    $mailConfig = require __DIR__ . '/mail.php';
    $mailer = new Mailer($mailConfig);
    $emailService = new EmailService($conn, $mailer);
    $passwordResetModel = new PasswordResetModel($conn);
    $passwordService = new PasswordService($passwordResetModel, $mailer);
    $emailVerificationController = new EmailVerificationController(
        $emailService,
        $userModel,
        $formateurModel,
        $entrepriseModel
    );
    
    // Système de réinitialisation avec TOKEN (nouveau)
    $tokenResetModel = new TokenResetModel($conn);
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/mon_site';
    $tokenService = new TokenService($tokenResetModel, $mailer, $baseUrl, 15, 5);
    $tokenResetController = new TokenResetController($tokenService);

    $aiConfig = require __DIR__ . '/ai.php';
    $aiSupportModel = new AiSupportRequestModel($conn);
    $aiService = new AiService($aiConfig);
    $aiSupportController = new AiSupportController($aiSupportModel, $aiService);

    $userController = new UserController($userModel, $emailService, $mailer);
    $formateurController = new FormateurController($formateurModel, $emailService, $mailer);
    $entrepriseController = new EntrepriseController($entrepriseModel, $emailService, $mailer);
    $signupValidationController = new SignupValidationController($signupValidator);
    $profileController = new ProfileController($userModel, $formateurModel, $entrepriseModel, $passwordService);

    // Nouvelles entités de jointure
    $inscEntrepriseModel = new InscriptionEntrepriseModel($conn);
    $inscFormateurModel = new InscriptionFormateurModel($conn);
    
    $inscEntrepriseController = new InscriptionEntrepriseController($inscEntrepriseModel, $userModel, $entrepriseModel);
    $inscFormateurController = new InscriptionFormateurController($inscFormateurModel, $userModel, $formateurModel);

    require_once __DIR__ . '/StatisticsController.php';
    $statisticsController = new StatisticsController();

    $controllerRoutes = [
        'login' => [$authController, 'login'],
        'logout' => [$authController, 'logout'],
        'verifier-email' => [$emailVerificationController, 'verify'],
        'mot-de-passe-oublie' => [$tokenResetController, 'requestReset'],
        'mot-de-passe-oublie-code' => [$tokenResetController, 'requestReset'],
        'mot-de-passe-oublie-reset' => [$tokenResetController, 'requestReset'],
        'reset-password-request' => [$tokenResetController, 'requestReset'],
        'reset-password-sent' => [$tokenResetController, 'resetPasswordSent'],
        'reset-password' => [$tokenResetController, 'resetPassword'],
        'dashboard' => [$dashboardController, 'index'],
        'utilisateur' => [$userController, 'signup'],
        'formateur' => [$formateurController, 'signup'],
        'entreprise' => [$entrepriseController, 'signup'],
        'api-utilisateurs' => [$userController, 'api'],
        'api-formateurs' => [$formateurController, 'api'],
        'api-entreprises' => [$entrepriseController, 'api'],
        'api-check-email' => [$signupValidationController, 'emailAvailability'],
        'ai-support' => [$aiSupportController, 'submit'],
        'demandes-ia' => [$aiSupportController, 'adminIndex'],
        'inscription-entreprise' => [$inscEntrepriseController, 'index'],
        'inscription-formateur' => [$inscFormateurController, 'index'],
        'statistiques' => [$statisticsController, 'index'],
        'dashboard-admin' => [$statisticsController, 'dashboardAdmin'],
        'api-statistiques' => [$statisticsController, 'api'],
        'profile' => [$profileController, 'index'],
        'metiers' => [$metiersController, 'index'],
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
