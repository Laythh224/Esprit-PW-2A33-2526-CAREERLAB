<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Controller\BaseController;
use App\Model\CritereModel;
use App\Model\FormationModel;

class CritereController extends BaseController
{
    private CritereModel $critereModel;
    private FormationModel $formationModel;

    public function __construct()
    {
        $this->critereModel = new CritereModel();
        $this->formationModel = new FormationModel();
    }

    public function index(): void
    {
        $formations = $this->formationModel->all();
        $formationSpecialites = [];
        foreach ($formations as $formation) {
            $name = (string) ($formation['nom_formation'] ?? '');
            if ($name === '') {
                continue;
            }
            $formationSpecialites[$name] = (string) ($formation['specialite'] ?? '');
        }

        $this->render('BackOffice/criteres', [
            'criteres' => $this->critereModel->all(),
            'formationChoices' => $this->formationModel->names(),
            'formationSpecialites' => $formationSpecialites,
            'flash' => $this->getFlash(),
            'oldInput' => $this->getOldInput(),
        ]);
    }

    public function store(): void
    {
        $postedInput = $this->extractPostedInput();
        $data = $this->validatedData();
        if ($data === null) {
            $this->setOldInput($postedInput);
            $this->setFlash('Donnees critere invalides.');
            $this->redirect('back/criteres');
        }

        $this->critereModel->create($data);
        $this->setFlash('Critere ajoute avec succes.');
        $this->redirect('back/criteres');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $postedInput = $this->extractPostedInput();
        $postedInput['_mode'] = 'update';
        $data = $this->validatedData();
        if ($id <= 0 || $data === null) {
            $this->setOldInput($postedInput);
            $this->setFlash('Donnees invalides pour la modification du critere.');
            $this->redirect('back/criteres');
        }

        $this->critereModel->update($id, $data);
        $this->setFlash('Critere modifie avec succes.');
        $this->redirect('back/criteres');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('Identifiant critere invalide.');
            $this->redirect('back/criteres');
        }

        $this->critereModel->delete($id);
        $this->setFlash('Critere supprime avec succes.');
        $this->redirect('back/criteres');
    }

    private function validatedData(): ?array
    {
        $nomFormation = trim((string) ($_POST['nom_formation'] ?? ''));
        $type = trim((string) ($_POST['type'] ?? ''));
        $lien = trim((string) ($_POST['lien'] ?? ''));
        $dureeOnline = trim((string) ($_POST['duree_online'] ?? ''));
        $adresse = trim((string) ($_POST['adresse'] ?? ''));
        $salle = trim((string) ($_POST['salle'] ?? ''));
        $dureePresentiel = trim((string) ($_POST['duree_presentiel'] ?? ''));

        if ($nomFormation === '' || $type === '') {
            return null;
        }

        if (!$this->formationModel->exists($nomFormation)) {
            return null;
        }

        if (!in_array($type, ['online', 'presentiel'], true)) {
            return null;
        }

        if ($type === 'online') {
            if ($lien === '' || $dureeOnline === '' || !ctype_digit($dureeOnline) || (int) $dureeOnline <= 0) {
                return null;
            }
            $adresse = null;
            $salle = null;
            $dureePresentiel = null;
        } else {
            if ($adresse === '' || $salle === '' || $dureePresentiel === '' || !ctype_digit($dureePresentiel) || (int) $dureePresentiel <= 0) {
                return null;
            }
            $lien = null;
            $dureeOnline = null;
        }

        return [
            'nom_formation' => $nomFormation,
            'type' => $type,
            'lien' => $lien !== null ? ($lien !== '' ? $lien : null) : null,
            'duree_online' => $dureeOnline !== null ? ($dureeOnline !== '' ? (int) $dureeOnline : null) : null,
            'adresse' => $adresse !== null ? ($adresse !== '' ? $adresse : null) : null,
            'salle' => $salle !== null ? ($salle !== '' ? $salle : null) : null,
            'duree_presentiel' => $dureePresentiel !== null ? ($dureePresentiel !== '' ? (int) $dureePresentiel : null) : null,
        ];
    }

    private function extractPostedInput(): array
    {
        return [
            'id' => (string) ($_POST['id'] ?? ''),
            'nom_formation' => trim((string) ($_POST['nom_formation'] ?? '')),
            'type' => trim((string) ($_POST['type'] ?? '')),
            'lien' => trim((string) ($_POST['lien'] ?? '')),
            'duree_online' => trim((string) ($_POST['duree_online'] ?? '')),
            'adresse' => trim((string) ($_POST['adresse'] ?? '')),
            'salle' => trim((string) ($_POST['salle'] ?? '')),
            'duree_presentiel' => trim((string) ($_POST['duree_presentiel'] ?? '')),
        ];
    }
}

