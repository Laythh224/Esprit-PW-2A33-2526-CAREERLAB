<?php

class FormateurController
{
    private FormateurModel $model;

    public function __construct(FormateurModel $model)
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

        require_once __DIR__ . '/../views/formateur.view.php';
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
                'diplomes' => '',
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
            ->setNom(trim((string) ($post['nom'] ?? '')))
            ->setPrenom(trim((string) ($post['prenom'] ?? '')))
            ->setEmail(trim((string) ($post['email'] ?? '')))
            ->setTelephone(trim((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setSpecialite(trim((string) ($post['specialite'] ?? '')))
            ->setDiplomes(trim((string) ($post['diplomes'] ?? '')))
            ->setExperience(trim((string) ($post['experience'] ?? '')));

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
            'diplomes' => $entity->getDiplomes(),
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
                'formateurs' => $this->model->all(),
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
                JsonResponse::send(true, ['message' => 'Formateur ajouté avec succès.']);
            }

            if ($action === 'update') {
                $this->model->update($input);
                JsonResponse::send(true, ['message' => 'Formateur modifié avec succès.']);
            }

            if ($action === 'delete') {
                $this->model->delete((int) ($input['id'] ?? 0));
                JsonResponse::send(true, ['message' => 'Formateur supprimé avec succès.']);
            }

            JsonResponse::send(false, ['message' => 'Action non supportée.'], 400);
        } catch (InvalidArgumentException $exception) {
            JsonResponse::send(false, ['message' => $exception->getMessage()], 422);
        }
    }
}
