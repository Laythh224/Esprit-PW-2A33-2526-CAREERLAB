<?php

class EntrepriseController
{
    private EntrepriseModel $model;

    public function __construct(EntrepriseModel $model)
    {
        $this->model = $model;
    }

    public function signup(): void
    {
        $formState = $this->createInitialSignupState();

        if (isset($_POST['submit'])) {
            $entity = $this->hydrateEntityFromPost($_POST);
            $formState['values'] = $this->extractValuesFromEntity($entity);
            $formState['errors'] = $this->model->validateSignup($entity);
            $this->clearInvalidFields($formState);

            if (!$this->hasSignupErrors($formState)) {
                try {
                    $this->model->registerFromEntity($entity);
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

        require_once __DIR__ . '/../views/entreprise.view.php';
    }

    private function createInitialSignupState(): array
    {
        return [
            'message' => '',
            'values' => [
                'nom' => '',
                'email' => '',
                'telephone' => '',
                'adresse' => '',
                'ville' => '',
                'secteur' => '',
                'description' => '',
                'site' => '',
                'password' => '',
                'confirm_password' => '',
            ],
            'errors' => [
                'nom' => '',
                'email' => '',
                'telephone' => '',
                'adresse' => '',
                'ville' => '',
                'secteur' => '',
                'description' => '',
                'site' => '',
                'password' => '',
                'confirm_password' => '',
            ],
        ];
    }

    private function hydrateEntityFromPost(array $post): EntrepriseEntity
    {
        $entity = new EntrepriseEntity();

        $entity
            ->setNom(trim((string) ($post['nom'] ?? '')))
            ->setEmail(trim((string) ($post['email'] ?? '')))
            ->setTelephone(trim((string) ($post['telephone'] ?? '')))
            ->setAdresse(trim((string) ($post['adresse'] ?? '')))
            ->setVille(trim((string) ($post['ville'] ?? '')))
            ->setSecteur(trim((string) ($post['secteur'] ?? '')))
            ->setDescription(trim((string) ($post['description'] ?? '')))
            ->setSite(trim((string) ($post['site'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''));

        return $entity;
    }

    private function extractValuesFromEntity(EntrepriseEntity $entity): array
    {
        return [
            'nom' => $entity->getNom(),
            'email' => $entity->getEmail(),
            'telephone' => $entity->getTelephone(),
            'adresse' => $entity->getAdresse(),
            'ville' => $entity->getVille(),
            'secteur' => $entity->getSecteur(),
            'description' => $entity->getDescription(),
            'site' => $entity->getSite(),
            'password' => $entity->getPassword(),
            'confirm_password' => $entity->getConfirmPassword(),
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

        if (str_contains($lowerMessage, 'site')) {
            $formState['errors']['site'] = $message;
            return;
        }

        $formState['message'] = $message;
    }

    public function api(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            JsonResponse::send(true, [
                'entreprises' => $this->model->all(),
                'stats' => ['total' => $this->model->count()],
            ]);
        }

        if ($method !== 'POST') {
            JsonResponse::send(false, ['message' => 'Méthode non autorisée.'], 405);
        }

        $rawInput = (string) file_get_contents('php://input');
        if (!mb_check_encoding($rawInput, 'UTF-8')) {
            $rawInput = mb_convert_encoding($rawInput, 'UTF-8', 'Windows-1252,ISO-8859-1,UTF-8');
        }

        $input = json_decode($rawInput, true);
        if (!is_array($input)) {
            JsonResponse::send(false, ['message' => 'Payload JSON invalide.'], 400);
        }

        $action = trim((string) ($input['action'] ?? ''));

        try {
            if ($action === 'create') {
                $this->model->create($input);
                JsonResponse::send(true, ['message' => 'Entreprise ajoutée avec succès.']);
            }

            if ($action === 'update') {
                $this->model->update($input);
                JsonResponse::send(true, ['message' => 'Entreprise modifiée avec succès.']);
            }

            if ($action === 'delete') {
                $this->model->delete((int) ($input['id'] ?? 0));
                JsonResponse::send(true, ['message' => 'Entreprise supprimée avec succès.']);
            }

            JsonResponse::send(false, ['message' => 'Action non supportée.'], 400);
        } catch (InvalidArgumentException $exception) {
            JsonResponse::send(false, ['message' => $exception->getMessage()], 422);
        }
    }
}
