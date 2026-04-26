<?php

class UserController
{
    private UserModel $model;

    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    public function signup(): void
    {
        $formState = $this->createInitialSignupState();

        if (isset($_POST['submit'])) {
            $entity = $this->hydrateEntityFromPost($_POST);
            $formState['values'] = $this->extractValuesFromEntity($entity);
            $formState['errors'] = $this->model->validateSignup($entity, $_FILES);
            $this->clearInvalidFields($formState);

            if (!$this->hasSignupErrors($formState)) {
                try {
                    $this->model->registerFromEntity($entity, $_FILES);
                    header('Location: index.php');
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
            ->setNom(trim((string) ($post['nom'] ?? '')))
            ->setPrenom(trim((string) ($post['prenom'] ?? '')))
            ->setEmail(trim((string) ($post['email'] ?? '')))
            ->setTelephone(trim((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setNiveau(trim((string) ($post['niveau'] ?? '')))
            ->setDomaine(trim((string) ($post['domaine'] ?? '')))
            ->setCompetences(trim((string) ($post['competences'] ?? '')));

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

        $formState['message'] = $message;
    }

    public function api(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            JsonResponse::send(true, [
                'users' => $this->model->all(),
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

            JsonResponse::send(false, ['message' => 'Action non supportée.'], 400);
        } catch (InvalidArgumentException $exception) {
            JsonResponse::send(false, ['message' => $exception->getMessage()], 422);
        }
    }
}

