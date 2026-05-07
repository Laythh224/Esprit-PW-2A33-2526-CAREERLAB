<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Controller\BaseController;
use App\Model\FormationModel;
use App\Model\SessionModel;

class SessionController extends BaseController
{
    private SessionModel $sessionModel;
    private FormationModel $formationModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
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

        $this->render('BackOffice/sessions', [
            'sessions' => $this->sessionModel->all(),
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
            $this->setFlash('Donnees session invalides.');
            $this->redirect('back/sessions');
        }

        $this->sessionModel->create($data);
        $this->setFlash('Session ajoutee avec succes.');
        $this->redirect('back/sessions');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $postedInput = $this->extractPostedInput();
        $postedInput['_mode'] = 'update';
        $data = $this->validatedData();
        if ($id <= 0 || $data === null) {
            $this->setOldInput($postedInput);
            $this->setFlash('Donnees invalides pour la modification de la session.');
            $this->redirect('back/sessions');
        }

        $this->sessionModel->update($id, $data);
        $this->setFlash('Session modifiee avec succes.');
        $this->redirect('back/sessions');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('Identifiant session invalide.');
            $this->redirect('back/sessions');
        }

        $this->sessionModel->delete($id);
        $this->setFlash('Session supprimee avec succes.');
        $this->redirect('back/sessions');
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
        $dateDebut = trim((string) ($_POST['date_debut'] ?? ''));
        $dateFin = trim((string) ($_POST['date_fin'] ?? ''));
        $nbPlace = (int) ($_POST['nb_place'] ?? 0);

        if ($nomFormation === '' || $type === '' || $dateDebut === '' || $dateFin === '' || $nbPlace <= 0) {
            return null;
        }

        if (!$this->formationModel->exists($nomFormation)) {
            return null;
        }

        if (!in_array($type, ['online', 'presentiel'], true)) {
            return null;
        }

        if (!$this->isValidDate($dateDebut) || !$this->isValidDate($dateFin)) {
            return null;
        }

        $today = new \DateTimeImmutable('today');
        $startDate = new \DateTimeImmutable($dateDebut);
        $endDate = new \DateTimeImmutable($dateFin);

        if ($startDate < $today) {
            return null;
        }

        if ($endDate <= $startDate) {
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
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_place' => $nbPlace,
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
            'date_debut' => trim((string) ($_POST['date_debut'] ?? '')),
            'date_fin' => trim((string) ($_POST['date_fin'] ?? '')),
            'nb_place' => (string) ($_POST['nb_place'] ?? ''),
        ];
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTimeImmutable::createFromFormat('Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
