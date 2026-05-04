<?php

class AiSupportController
{
    private AiSupportRequestModel $model;
    private AiService $assistant;

    public function __construct(AiSupportRequestModel $model, AiService $assistant)
    {
        $this->model = $model;
        $this->assistant = $assistant;
    }

    public function submit(): void
    {
        $this->ensureSessionStarted();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=accueil#support-ia');
            exit;
        }

        $state = $this->validate($_POST);
        if (!empty($state['errors'])) {
            $_SESSION['ai_support_flash'] = [
                'type' => 'danger',
                'message' => 'Veuillez corriger les champs du formulaire.',
                'errors' => $state['errors'],
                'old' => $state['old'],
            ];
            header('Location: index.php?page=accueil#support-ia');
            exit;
        }

        $old = $state['old'];
        $requestId = $this->model->create($old['name'], $old['email'], $old['message']);
        $aiResult = $this->assistant->answer($old['message'], $old['email'], $old['name']);

        $this->model->updateAiResponse(
            $requestId,
            (string) $aiResult['request_type'],
            (string) $aiResult['response'],
            (string) $aiResult['status']
        );

        $_SESSION['ai_support_flash'] = [
            'type' => 'success',
            'message' => 'Votre demande a ete analysee par l assistant IA.',
            'ai_response' => (string) $aiResult['response'],
            'request_type' => (string) $aiResult['request_type'],
        ];

        header('Location: index.php?page=accueil#support-ia');
        exit;
    }

    public function adminIndex(): void
    {
        $requests = $this->model->all();
        require __DIR__ . '/../Views/admin-ai-requests.view.php';
    }

    private function validate(array $post): array
    {
        $old = [
            'name' => $this->cleanText((string) ($post['name'] ?? '')),
            'email' => $this->cleanText((string) ($post['email'] ?? '')),
            'message' => $this->cleanText((string) ($post['message'] ?? '')),
        ];

        $errors = [];

        if ($old['email'] === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        } elseif (mb_strlen($old['email'], 'UTF-8') > 191) {
            $errors['email'] = "L'email ne doit pas depasser 191 caracteres.";
        }

        if ($old['name'] !== '' && mb_strlen($old['name'], 'UTF-8') > 120) {
            $errors['name'] = 'Le nom ne doit pas depasser 120 caracteres.';
        }

        if ($old['message'] === '') {
            $errors['message'] = 'Le message est obligatoire.';
        } elseif (mb_strlen($old['message'], 'UTF-8') < 10) {
            $errors['message'] = 'Le message doit contenir au moins 10 caracteres.';
        } elseif (mb_strlen($old['message'], 'UTF-8') > 2000) {
            $errors['message'] = 'Le message ne doit pas depasser 2000 caracteres.';
        }

        return [
            'old' => $old,
            'errors' => $errors,
        ];
    }

    private function cleanText(string $value): string
    {
        $value = trim(strip_tags($value));
        return preg_replace('/\s+/u', ' ', $value) ?? '';
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
