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
        $serverError = '';

        if (isset($_POST['submit'])) {
            try {
                $this->model->registerFromSignup($_POST);
                header('Location: index.php?page=login');
                exit;
            } catch (Throwable $exception) {
                $serverError = $exception->getMessage();
            }
        }

        require_once __DIR__ . '/../views/entreprise.view.php';
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
