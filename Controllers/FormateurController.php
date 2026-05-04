<?php

class FormateurController
{
    private FormateurModel $model;
    private EmailService $verificationService;
    private ?Mailer $mailer;

    public function __construct(FormateurModel $model, EmailService $verificationService, ?Mailer $mailer = null)
    {
        $this->model = $model;
        $this->verificationService = $verificationService;
        $this->mailer = $mailer;
    }

    public function signup(): void
    {
        $formState = $this->createInitialSignupState();

        if (isset($_POST['submit'])) {
            $_POST['telephone'] = $this->resolvePhoneNumber($_POST);
            $entity = $this->hydrateEntityFromPost($_POST);
            $formState['values'] = $this->extractValuesFromEntity($entity);
            $filesForValidation = [];
            if (isset($_FILES['cv'])) {
                $filesForValidation['cv'] = $_FILES['cv'];
            }
            if (isset($_FILES['diplome_files'])) {
                $filesForValidation['diplome_files'] = $_FILES['diplome_files'];
            }
            $formState['errors'] = $this->model->validateSignup($entity, $filesForValidation);
            $this->clearInvalidFields($formState);

            if (!$this->hasSignupErrors($formState)) {
                try {
                    $data = $this->model->prepareRegistrationData($entity, $_FILES);
                    $this->ensureSessionStarted();
                    $this->verificationService->startRegistrationSession('formateur', $entity->getEmail(), $data);
                    $_SESSION['verification_flash'] = [
                        'type' => 'success',
                        'message' => 'Un code de verification a ete envoye a votre email.',
                    ];

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

        require_once __DIR__ . '/../Views/formateur.view.php';
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
                'specialite' => '',
                'diplome_count' => '',
                'experience' => '',
            ],
            'errors' => [
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'telephone' => '',
                'password' => '',
                'confirm_password' => '',
                'specialite' => '',
                'diplomes' => '',
                'experience' => '',
                'cv' => '',
            ],
        ];
    }

    private function hydrateEntityFromPost(array $post): FormateurEntity
    {
        $entity = new FormateurEntity();

        $entity
            ->setNom($this->cleanText((string) ($post['nom'] ?? '')))
            ->setPrenom($this->cleanText((string) ($post['prenom'] ?? '')))
            ->setEmail($this->cleanEmail((string) ($post['email'] ?? '')))
            ->setTelephone($this->cleanText((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setSpecialite($this->cleanText((string) ($post['specialite'] ?? '')))
            ->setDiplomeCount((int) ($post['diplome_count'] ?? 0))
            ->setExperience($this->cleanMultilineText((string) ($post['experience'] ?? '')));

        return $entity;
    }

    private function extractValuesFromEntity(FormateurEntity $entity): array
    {
        return [
            'nom' => $entity->getNom(),
            'prenom' => $entity->getPrenom(),
            'email' => $entity->getEmail(),
            'telephone' => $entity->getTelephone(),
            'password' => $entity->getPassword(),
            'confirm_password' => $entity->getConfirmPassword(),
            'specialite' => $entity->getSpecialite(),
            'diplome_count' => $entity->getDiplomeCount() > 0 ? (string) $entity->getDiplomeCount() : '',
            'experience' => $entity->getExperience(),
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

        if (str_contains($lowerMessage, 'telephone')) {
            $formState['errors']['telephone'] = $message;
            return;
        }

        if (str_contains($lowerMessage, 'cv') || str_contains($lowerMessage, 'pdf')) {
            $formState['errors']['cv'] = $message;
            return;
        }

        $formState['message'] = $message;
    }

    private function resolvePhoneNumber(array $post): string
    {
        $phone = $this->cleanText((string) ($post['telephone'] ?? ''));
        if ($phone !== '') {
            return $phone;
        }

        return $this->cleanText((string) ($post['telephone_display'] ?? ''));
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
                'formateurs' => $this->model->all($verificationFilter),
                'stats' => ['total' => $this->model->count()],
            ]);
        }

        if ($method !== 'POST') {
            JsonResponse::send(false, ['message' => 'Methode non autorisee.'], 405);
        }

        $input = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($input)) {
            JsonResponse::send(false, ['message' => 'Payload JSON invalide.'], 400);
        }

        $action = trim((string) ($input['action'] ?? ''));

        try {
            if ($action === 'create') {
                $this->model->create($input);
                JsonResponse::send(true, ['message' => 'Formateur ajoute avec succes.']);
            }

            if ($action === 'update') {
                $this->model->update($input);
                JsonResponse::send(true, ['message' => 'Formateur modifie avec succes.']);
            }

            if ($action === 'delete') {
                $this->model->delete((int) ($input['id'] ?? 0));
                JsonResponse::send(true, ['message' => 'Formateur supprime avec succes.']);
            }

            if ($action === 'toggleVerification') {
                $this->toggleVerificationStatus((int) ($input['id'] ?? 0), (bool) ($input['verified'] ?? false));
                return;
            }

            JsonResponse::send(false, ['message' => 'Action non supportee.'], 400);
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
