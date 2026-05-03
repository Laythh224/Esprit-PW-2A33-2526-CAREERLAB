<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;
use App\Model\SessionModel;

class FormationController extends BaseController
{
    private SessionModel $sessionModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
    }

    public function index(): void
    {
        $rawRows = $this->sessionModel->byFormation();
        $formations = [];

        foreach ($rawRows as $row) {
            $nomFormation = (string) $row['nom_formation'];
            if (!isset($formations[$nomFormation])) {
                $formations[$nomFormation] = [
                    'nom_formation' => $nomFormation,
                    'specialite' => $row['specialite'],
                    'description' => $row['description'],
                    'niveau' => $row['niveau'],
                    'nb_place' => $row['nb_place'],
                    'sessions' => [],
                ];
            }

            if ($row['session_id'] !== null) {
                $formations[$nomFormation]['sessions'][] = [
                    'id' => (int) $row['session_id'],
                    'type' => $row['type'],
                    'lien' => $row['lien'],
                    'duree_online' => $row['duree_online'],
                    'adresse' => $row['adresse'],
                    'salle' => $row['salle'],
                    'duree_presentiel' => $row['duree_presentiel'],
                    'date_debut' => $row['date_debut'],
                    'date_fin' => $row['date_fin'],
                ];
            }
        }

        $this->render('FrontOffice/formations', [
            'title' => 'Formations',
            'active' => 'formations',
            'formations' => array_values($formations),
        ]);
    }
}
