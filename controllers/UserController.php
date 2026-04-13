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
        $serverError = '';

        if (isset($_POST['submit'])) {
            try {
                $this->model->registerFromSignup($_POST, $_FILES);
                header('Location: index.php?page=login');
                exit;
            } catch (Throwable $exception) {
                $serverError = $exception->getMessage();
            }
        }

        require_once __DIR__ . '/../views/utilisateur.view.php';
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
