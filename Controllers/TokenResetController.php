<?php

class TokenResetController
{
    private TokenService $service;

    public function __construct(TokenService $service)
    {
        $this->service = $service;
    }

    /**
     * Page "Mot de passe oublié" - Formulaire de demande
     */
    public function requestReset(): void
    {
        $this->ensureSessionStarted();

        $state = [
            'message' => '',
            'messageType' => 'info',
            'errors' => ['email' => ''],
            'emailValue' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->cleanInput((string) ($_POST['email'] ?? ''));
            $state['emailValue'] = $email;

            // Valider l'email
            if (empty($email)) {
                $state['errors']['email'] = "L'adresse email est obligatoire.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $state['errors']['email'] = 'Veuillez saisir une adresse email valide.';
            } else {
                // Demander la réinitialisation
                $result = $this->service->requestReset($email);

                // Afficher toujours le même message (sécurité)
                $_SESSION['reset_request_flash'] = [
                    'type' => 'info',
                    'message' => $result['message'],
                ];

                // Rediriger vers page de confirmation
                header('Location: index.php?page=reset-password-sent');
                exit;
            }
        }

        require __DIR__ . '/../Views/reset-password-request.view.php';
    }

    /**
     * Page de confirmation (après demande)
     */
    public function resetPasswordSent(): void
    {
        $this->ensureSessionStarted();

        $message = '';
        $messageType = 'info';

        if (isset($_SESSION['reset_request_flash'])) {
            $flash = $_SESSION['reset_request_flash'];
            $message = (string) ($flash['message'] ?? '');
            $messageType = (string) ($flash['type'] ?? 'info');
            unset($_SESSION['reset_request_flash']);
        }

        require __DIR__ . '/../Views/reset-password-sent.view.php';
    }

    /**
     * Page de réinitialisation du mot de passe (avec token dans l'URL)
     */
    public function resetPassword(): void
    {
        $this->ensureSessionStarted();

        $token = (string) ($_GET['token'] ?? '');
        $message = '';
        $messageType = 'info';
        $state = [
            'message' => '',
            'messageType' => 'info',
            'errors' => [
                'password' => '',
                'confirm_password' => '',
            ],
        ];

        // Valider le token
        if (empty($token)) {
            $message = 'Lien de réinitialisation manquant.';
            $messageType = 'danger';
            require __DIR__ . '/../Views/reset-password-error.view.php';
            return;
        }

        $tokenValidation = $this->service->validateToken($token);
        if (!$tokenValidation['ok']) {
            $message = $tokenValidation['message'];
            $messageType = 'danger';
            require __DIR__ . '/../Views/reset-password-error.view.php';
            return;
        }

        $email = (string) $tokenValidation['email'];

        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = (string) ($_POST['password'] ?? '');
            $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

            // Valider et réinitialiser
            $result = $this->service->resetPassword($token, $email, $password, $confirmPassword);

            if ($result['ok']) {
                // Succès - Rediriger vers login avec message
                $_SESSION['login_flash'] = [
                    'type' => 'success',
                    'message' => $result['message'],
                ];
                header('Location: index.php?page=login');
                exit;
            } else {
                // Erreur
                if (isset($result['errors'])) {
                    $state['errors'] = $result['errors'];
                } else {
                    $state['message'] = $result['message'];
                    $state['messageType'] = 'danger';
                }
            }
        }

        $state['token'] = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');

        require __DIR__ . '/../Views/reset-password-reset.view.php';
    }

    /**
     * Nettoyer l'input
     */
    private function cleanInput(string $input): string
    {
        return trim(strtolower(strip_tags($input)));
    }

    /**
     * Assurer que la session est démarrée
     */
    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
