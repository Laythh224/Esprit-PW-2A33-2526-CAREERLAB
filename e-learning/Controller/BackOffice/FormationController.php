<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Controller\BaseController;
use App\Model\FormationModel;

class FormationController extends BaseController
{
    private FormationModel $model;

    public function __construct()
    {
        $this->model = new FormationModel();
    }

    public function index(): void
    {
        $this->render('BackOffice/formations', [
            'formations' => $this->model->all(),
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
            $this->setFlash('Donnees formation invalides.');
            $this->redirect('back/formations');
        }

        if ($this->model->exists($data['nom_formation'])) {
            $this->setOldInput($postedInput);
            $this->setFlash('Une formation avec ce nom existe deja.');
            $this->redirect('back/formations');
        }

        $this->model->create($data);
        $this->setFlash('Formation ajoutee avec succes.');
        $this->redirect('back/formations');
    }

    public function update(): void
    {
        $oldNomFormation = $this->normalizeSpaces($_POST['old_nom_formation'] ?? '');
        $postedInput = $this->extractPostedInput();
        $postedInput['_mode'] = 'update';
        $data = $this->validatedData();
        if ($oldNomFormation === '' || $data === null) {
            $this->setOldInput($postedInput);
            $this->setFlash('Donnees invalides pour la modification.');
            $this->redirect('back/formations');
        }

        if ($oldNomFormation !== $data['nom_formation'] && $this->model->exists($data['nom_formation'])) {
            $this->setOldInput($postedInput);
            $this->setFlash('Le nouveau nom de formation existe deja.');
            $this->redirect('back/formations');
        }

        $this->model->update($oldNomFormation, $data);
        $this->setFlash('Formation modifiee avec succes.');
        $this->redirect('back/formations');
    }

    public function delete(): void
    {
        $nomFormation = $this->normalizeSpaces($_POST['nom_formation'] ?? '');
        if ($nomFormation === '') {
            $this->setFlash('Formation invalide pour la suppression.');
            $this->redirect('back/formations');
        }

        $this->model->delete($nomFormation);
        $this->setFlash('Formation supprimee avec succes.');
        $this->redirect('back/formations');
    }

    private function validatedData(): ?array
    {
        $nomFormation = $this->normalizeSpaces($_POST['nom_formation'] ?? '');
        $specialite = $this->normalizeSpaces($_POST['specialite'] ?? '');
        $description = $this->normalizeSpaces($_POST['description'] ?? '');
        $dateDebut = $this->normalizeSpaces($_POST['date_debut'] ?? '');
        $dateFin = $this->normalizeSpaces($_POST['date_fin'] ?? '');
        $niveau = $this->normalizeSpaces($_POST['niveau'] ?? '');
        $duree = (int) ($_POST['duree'] ?? 0);

        if ($nomFormation === '' || $specialite === '' || $description === '' || $dateDebut === '' || $dateFin === '' || $niveau === '' || $duree <= 0) {
            return null;
        }

        if (!$this->isValidDate($dateDebut) || !$this->isValidDate($dateFin)) {
            return null;
        }

        $today = new \DateTimeImmutable('today');
        $startDate = new \DateTimeImmutable($dateDebut);
        $endDate = new \DateTimeImmutable($dateFin);

        // date_debut cannot be in the past.
        if ($startDate < $today) {
            return null;
        }

        // Must be at least one day apart: date_fin strictly after date_debut.
        if ($endDate <= $startDate) {
            return null;
        }

        return [
            'nom_formation' => $nomFormation,
            'specialite' => $specialite,
            'description' => $description,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'niveau' => $niveau,
            'duree' => $duree,
        ];
    }

    private function extractPostedInput(): array
    {
        return [
            'old_nom_formation' => $this->normalizeSpaces($_POST['old_nom_formation'] ?? ''),
            'nom_formation' => $this->normalizeSpaces($_POST['nom_formation'] ?? ''),
            'specialite' => $this->normalizeSpaces($_POST['specialite'] ?? ''),
            'description' => $this->normalizeSpaces($_POST['description'] ?? ''),
            'date_debut' => $this->normalizeSpaces($_POST['date_debut'] ?? ''),
            'date_fin' => $this->normalizeSpaces($_POST['date_fin'] ?? ''),
            'niveau' => $this->normalizeSpaces($_POST['niveau'] ?? ''),
            'duree' => (string) ($_POST['duree'] ?? ''),
        ];
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}

