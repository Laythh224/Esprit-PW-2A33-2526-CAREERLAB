<?php

class AuthController
{
    private const CAPTCHA_THRESHOLD = 2;
    private UserModel $userModel;
    private FormateurModel $formateurModel;
    private EntrepriseModel $entrepriseModel;

    public function __construct(UserModel $userModel, FormateurModel $formateurModel, EntrepriseModel $entrepriseModel)
    {
        $this->userModel = $userModel;
        $this->formateurModel = $formateurModel;
        $this->entrepriseModel = $entrepriseModel;
    }

    public function processLogin(): array
    {
        $this->ensureSessionStarted();
        $state = $this->createInitialLoginState();
        $recaptchaConfig = $this->getRecaptchaConfig();
        $state['recaptchaSiteKey'] = (string) ($recaptchaConfig['site_key'] ?? '');
        $state['requireCaptcha'] = $this->shouldRequireCaptcha($recaptchaConfig);

        if (!isset($_POST['login'])) {
            return $state;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $state['emailValue'] = $email;
        $state['passwordValue'] = $password;

        $this->fillCredentialErrors($state, $email, $password);

        // Keep only valid fields after submit; invalid ones are cleared.
        $this->clearInvalidFieldValues($state);

        if ($this->hasCredentialErrors($state)) {
            return $state;
        }

        if ($state['requireCaptcha']) {
            $captchaResponse = trim((string) ($_POST['g-recaptcha-response'] ?? ''));
            if ($captchaResponse === '') {
                $state['errors']['captcha'] = 'Veuillez verifier que vous n\'etes pas un robot.';
                $this->incrementLoginFailedAttempts();
                $state['requireCaptcha'] = $this->shouldRequireCaptcha($recaptchaConfig);
                return $state;
            }

            if (!$this->verifyRecaptcha($captchaResponse, $recaptchaConfig)) {
                $state['errors']['captcha'] = 'Veuillez verifier que vous n\'etes pas un robot.';
                $this->incrementLoginFailedAttempts();
                $state['requireCaptcha'] = $this->shouldRequireCaptcha($recaptchaConfig);
                return $state;
            }
        }

        if ($this->authenticateAndRedirect($email, $password)) {
            return $state;
        }

        $this->incrementLoginFailedAttempts();
        $state['requireCaptcha'] = $this->shouldRequireCaptcha($recaptchaConfig);
        $state['message'] = 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.';

        return $state;
    }

    public function login(): void
    {
        $loginState = $this->processLogin();
        $this->renderLoginView($loginState);
    }

    public function logout(): void
    {
        $this->ensureSessionStarted();
        $_SESSION = [];
        session_unset();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: index.php');
        exit;
    }

    private function createInitialLoginState(): array
    {
        return [
            'message' => '',
            'errors' => [
                'email' => '',
                'password' => '',
                'captcha' => '',
            ],
            'emailValue' => '',
            'passwordValue' => '',
            'requireCaptcha' => false,
            'recaptchaSiteKey' => '',
        ];
    }

    private function clearInvalidFieldValues(array &$state): void
    {
        if (($state['errors']['email'] ?? '') !== '') {
            $state['emailValue'] = '';
        }

        if (($state['errors']['password'] ?? '') !== '') {
            $state['passwordValue'] = '';
        }
    }

    private function fillCredentialErrors(array &$state, string $email, string $password): void
    {
        if ($email === '') {
            $state['errors']['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $state['errors']['email'] = 'Veuillez saisir une adresse email valide.';
        }

        if ($password === '') {
            $state['errors']['password'] = 'Le mot de passe est obligatoire.';
        }
    }

    private function hasCredentialErrors(array $state): bool
    {
        return $state['errors']['email'] !== '' || $state['errors']['password'] !== '';
    }

    private function authenticateAndRedirect(string $email, string $password): bool
    {
        $sources = [
            ['role' => 'utilisateur', 'lookup' => fn () => $this->userModel->findByEmail($email)],
            ['role' => 'formateur', 'lookup' => fn () => $this->formateurModel->findByEmail($email)],
            ['role' => 'entreprise', 'lookup' => fn () => $this->entrepriseModel->findByEmail($email)],
        ];

        foreach ($sources as $entry) {
            $account = $entry['lookup']();

            if ($account && $this->isMatchingPassword($password, (string) $account['password'])) {
                $this->resetLoginFailedAttempts();
                if (array_key_exists('email_verified', $account) && (int) $account['email_verified'] === 0) {
                    $this->storePendingVerification($entry['role'], (string) $account['email']);
                    header('Location: index.php?page=verifier-email');
                    exit;
                }
                $this->storeAuthenticatedSession($entry['role'], $account);
                $this->redirectToDashboard();
            }
        }

        return false;
    }

    private function isMatchingPassword(string $plainPassword, string $storedPassword): bool
    {
        return password_verify($plainPassword, $storedPassword)
            || hash_equals($storedPassword, $plainPassword);
    }

    private function storeAuthenticatedSession(string $role, array $account): void
    {
        $this->ensureSessionStarted();
        $sessionRole = strtolower(trim((string) ($account['role'] ?? '')));
        if ($sessionRole === '') {
            $sessionRole = $role === 'admin' ? 'admin' : 'user';
        }

        $_SESSION['role'] = $sessionRole;
        $_SESSION['user_email'] = $account['email'];
        $_SESSION['user_id'] = $account['id'] ?? null;
        $_SESSION['is_logged_in'] = true;

        if ($role === 'entreprise') {
            $_SESSION['user_name'] = $account['nom_entreprise'] ?? $account['nom'] ?? $account['email'];
            $_SESSION['nom'] = $_SESSION['user_name'];
            $this->forceSessionCookie();
            return;
        }

        $_SESSION['user_name'] = trim(($account['prenom'] ?? '') . ' ' . ($account['nom'] ?? '')) ?: $account['email'];
        $_SESSION['nom'] = $_SESSION['user_name'];
    }

    private function storePendingVerification(string $role, string $email): void
    {
        $this->ensureSessionStarted();
        $_SESSION['pending_verification'] = [
            'email' => $email,
            'role' => $role,
        ];
        $_SESSION['verification_flash'] = [
            'type' => 'warning',
            'message' => 'Veuillez verifier votre email avant de vous connecter.',
        ];
    }

    private function redirectToDashboard(): void
    {
        session_write_close();
        header('Location: index.php');
        exit;
    }

    private function renderLoginView(array $loginState): void
    {
        $message = $loginState['message'];
        $errors = $loginState['errors'];
        $emailValue = $loginState['emailValue'];
        $passwordValue = $loginState['passwordValue'];
        $requireCaptcha = $loginState['requireCaptcha'];
        $recaptchaSiteKey = $loginState['recaptchaSiteKey'];

        require_once __DIR__ . '/../Views/login.view.php';
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function shouldRequireCaptcha(array $recaptchaConfig): bool
    {
        if (empty($recaptchaConfig['enabled']) || empty($recaptchaConfig['site_key']) || empty($recaptchaConfig['secret_key'])) {
            return false;
        }

        return $this->getLoginFailedAttempts() >= self::CAPTCHA_THRESHOLD;
    }

    private function getLoginFailedAttempts(): int
    {
        return (int) ($_SESSION['login_failed_attempts'] ?? 0);
    }

    private function incrementLoginFailedAttempts(): void
    {
        $_SESSION['login_failed_attempts'] = $this->getLoginFailedAttempts() + 1;
    }

    private function resetLoginFailedAttempts(): void
    {
        $_SESSION['login_failed_attempts'] = 0;
    }

    private function getRecaptchaConfig(): array
    {
        $path = __DIR__ . '/../config/recaptcha.php';
        if (!file_exists($path)) {
            return [];
        }

        $config = require $path;
        return is_array($config) ? $config : [];
    }

    private function verifyRecaptcha(string $response, array $recaptchaConfig): bool
    {
        $secret = (string) ($recaptchaConfig['secret_key'] ?? '');
        if ($secret === '') {
            return false;
        }

        $payload = http_build_query([
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $result = null;

        if (function_exists('curl_init')) {
            $ch = curl_init($verifyUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 8,
                ],
            ]);
            $result = @file_get_contents($verifyUrl, false, $context);
        }

        if ($result === false || $result === null) {
            return false;
        }

        $decoded = json_decode($result, true);
        return is_array($decoded) && !empty($decoded['success']);
    }
}

