<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;
use App\Model\CritereModel;

class FormationController extends BaseController
{
    private CritereModel $critereModel;

    public function __construct()
    {
        $this->critereModel = new CritereModel();
    }

    public function index(): void
    {
        $rawRows = $this->critereModel->byFormation();
        $formations = [];

        foreach ($rawRows as $row) {
            $nomFormation = (string) $row['nom_formation'];
            if (!isset($formations[$nomFormation])) {
                $formations[$nomFormation] = [
                    'nom_formation' => $nomFormation,
                    'specialite' => $row['specialite'],
                    'description' => $row['description'],
                    'date_debut' => $row['date_debut'],
                    'date_fin' => $row['date_fin'],
                    'niveau' => $row['niveau'],
                    'duree' => $row['duree'],
                    'criteres' => [],
                ];
            }

            if ($row['critere_id'] !== null) {
                $formations[$nomFormation]['criteres'][] = [
                    'id' => (int) $row['critere_id'],
                    'type' => $row['type'],
                    'lien' => $row['lien'],
                    'duree_online' => $row['duree_online'],
                    'adresse' => $row['adresse'],
                    'salle' => $row['salle'],
                    'duree_presentiel' => $row['duree_presentiel'],
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

