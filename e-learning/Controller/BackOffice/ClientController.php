<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Controller\BaseController;
use App\Model\ClientModel;
use App\Model\Database;
use App\Model\SessionModel;

class ClientController extends BaseController
{
    private ClientModel $model;
    private SessionModel $sessionModel;

    public function __construct()
    {
        $this->model = new ClientModel();
        $this->sessionModel = new SessionModel();
    }

    public function index(): void
    {
        $this->render('BackOffice/clients', [
            'clients' => $this->model->all(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function delete(): void
    {
        $cin = $this->normalizeSpaces($_POST['cin'] ?? '');
        if ($cin === '' || !preg_match('/^\d{8}$/', $cin)) {
            $this->setFlash('CIN invalide pour la suppression.');
            $this->redirect('back/clients');
        }

        $client = $this->model->find($cin);
        if ($client === null) {
            $this->setFlash('Client introuvable.');
            $this->redirect('back/clients');
        }

        $sessionId = isset($client['session_id']) ? (int) $client['session_id'] : 0;

        $db = Database::connection();
        $db->beginTransaction();
        try {
            $this->model->delete($cin);
            if ($sessionId > 0) {
                $this->sessionModel->incrementNbPlace($sessionId);
            }
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            $this->setFlash('Erreur lors de la suppression.');
            $this->redirect('back/clients');
        }

        $this->setFlash('Client supprime avec succes.');
        $this->redirect('back/clients');
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }
}
