<?php

class AuthController
{
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

        if ($this->authenticateAndRedirect($email, $password)) {
            return $state;
        }

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
            ],
            'emailValue' => '',
            'passwordValue' => '',
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

        require_once __DIR__ . '/../Views/login.view.php';
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

