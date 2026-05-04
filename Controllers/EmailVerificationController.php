<?php

class EmailVerificationController
{
    private EmailService $service;
    private UserModel $userModel;
    private FormateurModel $formateurModel;
    private EntrepriseModel $entrepriseModel;

    public function __construct(
        EmailService $service,
        UserModel $userModel,
        FormateurModel $formateurModel,
        EntrepriseModel $entrepriseModel
    ) {
        $this->service = $service;
        $this->userModel = $userModel;
        $this->formateurModel = $formateurModel;
        $this->entrepriseModel = $entrepriseModel;
    }

    public function verify(): void
    {
        $this->ensureSessionStarted();

        $context = $this->getContext();
        $message = '';
        $messageType = 'info';
        $email = $context['email'] ?? '';
        $role = $context['role'] ?? '';
        $data = $context['data'] ?? [];

        if ($context['error'] !== '') {
            $message = $context['error'];
            $messageType = 'danger';
        }

        $flash = $_SESSION['verification_flash'] ?? null;
        if (is_array($flash)) {
            $message = (string) ($flash['message'] ?? $message);
            $messageType = (string) ($flash['type'] ?? $messageType);
            unset($_SESSION['verification_flash']);
        }

        $isVerified = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email !== '' && $role !== '') {
            $action = (string) ($_POST['action'] ?? 'verify');

            if ($action === 'resend') {
                try {
                    $this->service->resendSessionCode();
                    $message = 'Un nouveau code a ete envoye a votre email.';
                    $messageType = 'success';
                } catch (Throwable $exception) {
                    $message = $exception->getMessage();
                    $messageType = 'danger';
                }
            } else {
                $code = preg_replace('/\D+/', '', (string) ($_POST['code'] ?? '')) ?? '';
                if ($code === '') {
                    $message = 'Veuillez saisir le code.';
                    $messageType = 'danger';
                } elseif (!preg_match('/^\d{6}$/', $code)) {
                    $message = 'Le code doit contenir 6 chiffres.';
                    $messageType = 'danger';
                } else {
                    $result = $this->service->verifySessionCode($code);
                    $message = $result['message'];
                    $messageType = $result['ok'] ? 'success' : 'danger';
                    if ($result['ok']) {
                        try {
                            $account = null;
                            if ($role === 'utilisateur') {
                                $this->userModel->create($data);
                                $account = $this->userModel->findByEmail($email);
                            } elseif ($role === 'formateur') {
                                $this->formateurModel->create($data);
                                $account = $this->formateurModel->findByEmail($email);
                            } elseif ($role === 'entreprise') {
                                $this->entrepriseModel->create($data);
                                $account = $this->entrepriseModel->findByEmail($email);
                            }
                            $this->service->clearPendingSession();
                            
                            if ($account) {
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
                                } else {
                                    $_SESSION['user_name'] = trim(($account['prenom'] ?? '') . ' ' . ($account['nom'] ?? '')) ?: $account['email'];
                                    $_SESSION['nom'] = $_SESSION['user_name'];
                                }
                                
                                header('Location: index.php');
                                exit;
                            }
                            
                            $isVerified = true;
                        } catch (Throwable $e) {
                            $message = "Erreur lors de la creation du compte : " . $e->getMessage();
                            $messageType = 'danger';
                        }
                    }
                }
            }
        }

        $attemptsLeft = $email !== '' && $role !== '' ? $this->service->attemptsLeft() : 0;
        $resendWaitSeconds = $email !== '' && $role !== '' ? $this->service->resendWaitSeconds() : 0;
        $expiresInSeconds = $email !== '' && $role !== '' ? $this->service->expiresInSeconds() : 0;

        require_once __DIR__ . '/../Views/verify-email.view.php';
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getContext(): array
    {
        $context = $this->service->getPendingSessionData();
        if (!is_array($context)) {
            return [
                'email' => '',
                'role' => '',
                'data' => [],
                'error' => 'Session de verification expiree. Reconnectez-vous ou recréez un compte.',
            ];
        }

        return [
            'email' => (string) ($context['email'] ?? ''),
            'role' => (string) ($context['role'] ?? ''),
            'data' => (array) ($context['data'] ?? []),
            'error' => '',
        ];
    }
}
