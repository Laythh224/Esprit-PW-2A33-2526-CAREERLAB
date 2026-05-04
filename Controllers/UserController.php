<?php

class UserController
{
    private UserModel $model;
    private EmailService $verificationService;
    private ?Mailer $mailer;

    public function __construct(UserModel $model, EmailService $verificationService, ?Mailer $mailer = null)
    {
        $this->model = $model;
        $this->verificationService = $verificationService;
        $this->mailer = $mailer;
    }

    public function signup(): void
    {
        $formState = $this->createInitialSignupState();

        if (isset($_POST['submit'])) {
            $_POST['telephone'] = $this->normalizePhoneValue($_POST);
            $entity = $this->hydrateEntityFromPost($_POST);
            $formState['values'] = $this->extractValuesFromEntity($entity);
            $formState['errors'] = $this->model->validateSignup($entity, $_FILES);
            $this->clearInvalidFields($formState);

            if (!$this->hasSignupErrors($formState)) {
                try {
                    $data = $this->model->prepareRegistrationData($entity, $_FILES);
                    $this->ensureSessionStarted();
                    try {
                        $this->verificationService->startRegistrationSession('utilisateur', $entity->getEmail(), $data);
                        $_SESSION['verification_flash'] = [
                            'type' => 'success',
                            'message' => 'Un code de verification a ete envoye a votre email.',
                        ];
                    } catch (Throwable $exception) {
                        $_SESSION['verification_flash'] = [
                            'type' => 'warning',
                            'message' => 'Erreur: ' . $exception->getMessage(),
                        ];
                    }

                    header('Location: index.php?page=verifier-email');
                    exit;
                } catch (Throwable $exception) {
                    $this->mapSignupExceptionToFieldError($formState, $exception);
                    $this->clearInvalidFields($formState);
                }
            }
        }

        $serverError = $formState['message'];
        $old = $formState['values'];
        $fieldErrors = $formState['errors'];

        require_once __DIR__ . '/../Views/utilisateur.view.php';
    }

    private function createInitialSignupState(): array
    {
        return [
            'message' => '',
            'values' => [
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'telephone' => '',
                'password' => '',
                'confirm_password' => '',
                'niveau' => '',
                'domaine' => '',
                'competences' => '',
            ],
            'errors' => [
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'telephone' => '',
                'password' => '',
                'confirm_password' => '',
                'niveau' => '',
                'domaine' => '',
                'cv' => '',
            ],
        ];
    }

    private function hydrateEntityFromPost(array $post): UserEntity
    {
        $entity = new UserEntity();

        $entity
            ->setNom($this->cleanText((string) ($post['nom'] ?? '')))
            ->setPrenom($this->cleanText((string) ($post['prenom'] ?? '')))
            ->setEmail($this->cleanEmail((string) ($post['email'] ?? '')))
            ->setTelephone($this->normalizePhoneValue($post))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setNiveau($this->cleanText((string) ($post['niveau'] ?? '')))
            ->setDomaine($this->cleanText((string) ($post['domaine'] ?? '')))
            ->setCompetences($this->cleanMultilineText((string) ($post['competences'] ?? '')));

        return $entity;
    }

    private function extractValuesFromEntity(UserEntity $entity): array
    {
        return [
            'nom' => $entity->getNom(),
            'prenom' => $entity->getPrenom(),
            'email' => $entity->getEmail(),
            'telephone' => $entity->getTelephone(),
            'password' => $entity->getPassword(),
            'confirm_password' => $entity->getConfirmPassword(),
            'niveau' => $entity->getNiveau(),
            'domaine' => $entity->getDomaine(),
            'competences' => $entity->getCompetences(),
        ];
    }

    private function hasSignupErrors(array $formState): bool
    {
        foreach ($formState['errors'] as $error) {
            if ($error !== '') {
                return true;
            }
        }

        return false;
    }

    private function clearInvalidFields(array &$formState): void
    {
        foreach ($formState['errors'] as $field => $error) {
            if ($error === '' || !array_key_exists($field, $formState['values'])) {
                continue;
            }

            $formState['values'][$field] = '';
        }
    }

    private function mapSignupExceptionToFieldError(array &$formState, Throwable $exception): void
    {
        $message = $exception->getMessage();
        $lowerMessage = mb_strtolower($message);

        if (str_contains($lowerMessage, 'email') && (str_contains($lowerMessage, 'utilise') || str_contains($lowerMessage, 'existe'))) {
            $formState['errors']['email'] = 'Cet email est deja utilise.';
            return;
        }

        if (str_contains($lowerMessage, 'cv') || str_contains($lowerMessage, 'pdf')) {
            $formState['errors']['cv'] = $message;
            return;
        }

        if (str_contains($lowerMessage, 'telephone')) {
            $formState['errors']['telephone'] = $message;
            return;
        }

        $formState['message'] = $message;
    }

    private function normalizePhoneValue(array $post): string
    {
        $rawPhone = trim((string) ($post['telephone'] ?? ''));
        if ($rawPhone === '') {
            $rawPhone = trim((string) ($post['full_phone'] ?? ''));
        }
        if ($rawPhone === '') {
            $rawPhone = trim((string) ($post['phone'] ?? ''));
        }

        $sanitised = preg_replace('/[^\d+]/', '', $rawPhone) ?? '';
        return preg_replace('/(?!^)\+/', '', $sanitised) ?? '';
    }

    private function cleanText(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function cleanMultilineText(string $value): string
    {
        return trim(strip_tags(str_replace(["\r\n", "\r"], "\n", $value)));
    }

    private function cleanEmail(string $value): string
    {
        return trim(mb_strtolower(strip_tags($value), 'UTF-8'));
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function api(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $this->ensureAdminApiAccess();

        if ($method === 'GET') {
            $verificationFilter = $this->sanitizeVerificationFilter((string) ($_GET['verified'] ?? 'all'));
            JsonResponse::send(true, [
                'users' => $this->model->all($verificationFilter),
                'stats' => ['total' => $this->model->count()],
            ]);
        }

        if ($method !== 'POST') {
            JsonResponse::send(false, ['message' => 'Méthode non autorisée.'], 405);
        }

        $input = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($input)) {
            JsonResponse::send(false, ['message' => 'Payload JSON invalide.'], 400);
        }

        $action = trim((string) ($input['action'] ?? ''));

        try {
            if ($action === 'create') {
                $this->model->create($input);
                JsonResponse::send(true, ['message' => 'Utilisateur ajouté avec succès.']);
            }

            if ($action === 'update') {
                $this->model->update($input);
                JsonResponse::send(true, ['message' => 'Utilisateur modifié avec succès.']);
            }

            if ($action === 'delete') {
                $this->model->delete((int) ($input['id'] ?? 0));
                JsonResponse::send(true, ['message' => 'Utilisateur supprimé avec succès.']);
            }

            if ($action === 'toggleVerification') {
                $this->toggleVerificationStatus((int) ($input['id'] ?? 0), (bool) ($input['verified'] ?? false));
                return;
            }

            JsonResponse::send(false, ['message' => 'Action non supportée.'], 400);
        } catch (InvalidArgumentException $exception) {
            JsonResponse::send(false, ['message' => $exception->getMessage()], 422);
        }
    }

    private function toggleVerificationStatus(int $id, bool $verified): void
    {
        $account = $this->model->findById($id);
        if ($account === null) {
            JsonResponse::send(false, ['message' => 'Compte introuvable.'], 404);
        }

        $adminId = (int) ($_SESSION['user_id'] ?? 0);
        $previousVerified = (int) ($account['verified'] ?? 0) === 1;
        $this->model->updateVerificationStatus($id, $verified, $adminId > 0 ? $adminId : null);
        $this->model->logVerificationChange($id, $adminId > 0 ? $adminId : null, $previousVerified, $verified);

        if ($verified && $this->mailer !== null) {
            try {
                $displayName = trim((string) ($account['prenom'] ?? '') . ' ' . (string) ($account['nom'] ?? '')) ?: (string) ($account['email'] ?? '');
                $this->mailer->sendAccountVerifiedNotification((string) $account['email'], $displayName);
            } catch (Throwable $exception) {
                error_log('Account verification email failed: ' . $exception->getMessage());
            }
        }

        JsonResponse::send(true, [
            'message' => $verified ? 'Compte vérifié avec succès.' : 'Vérification retirée avec succès.',
        ]);
    }

    private function sanitizeVerificationFilter(string $value): string
    {
        $value = strtolower(trim($value));

        return in_array($value, ['verified', 'unverified'], true) ? $value : 'all';
    }

    private function ensureAdminApiAccess(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            JsonResponse::send(false, ['message' => 'Accès administrateur requis.'], 403);
        }
    }
}
