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
        $state = $this->createInitialLoginState();

        if (!isset($_POST['login'])) {
            return $state;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $state['emailValue'] = $email;

        $this->fillCredentialErrors($state, $email, $password);
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
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: index.php?page=login');
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
        ];
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
        $_SESSION['role'] = $role;
        $_SESSION['user_email'] = $account['email'];
        $_SESSION['user_id'] = $account['id'] ?? null;

        if ($role === 'entreprise') {
            $_SESSION['user_name'] = $account['nom_entreprise'] ?? $account['nom'] ?? $account['email'];
            return;
        }

        $_SESSION['user_name'] = trim(($account['prenom'] ?? '') . ' ' . ($account['nom'] ?? '')) ?: $account['email'];
    }

    private function redirectToDashboard(): void
    {
        header('Location: index.php?page=dashboard');
        exit;
    }

    private function renderLoginView(array $loginState): void
    {
        $message = $loginState['message'];
        $errors = $loginState['errors'];
        $emailValue = $loginState['emailValue'];

        require_once __DIR__ . '/../views/login.view.php';
    }
}
