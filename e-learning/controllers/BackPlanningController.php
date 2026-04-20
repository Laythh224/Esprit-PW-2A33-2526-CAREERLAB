<?php

namespace App\controllers;

use App\controllers\BaseController;
use App\models\PlanningModel;

class BackPlanningController extends BaseController
{
    private PlanningModel $model;

    public function __construct()
    {
        $this->model = new PlanningModel();
    }

    public function index(): void
    {
        $rows = $this->model->all();

        $today = new \DateTimeImmutable('today');
        foreach ($rows as &$row) {
            $start = new \DateTimeImmutable($row['date_debut']);
            $end = new \DateTimeImmutable($row['date_fin']);
            $row['duree_jours'] = (int) $start->diff($end)->format('%a');

            if ($today < $start) {
                $row['statut'] = 'À venir';
            } elseif ($today > $end) {
                $row['statut'] = 'Terminé';
            } else {
                $row['statut'] = 'En cours';
            }
        }

        $this->render('back/planning', [
            'planningRows' => $rows,
            'flash' => $this->getFlash(),
        ]);
    }

    public function store(): void
    {
        $data = $this->validatedData();
        if ($data === null) {
            $this->redirect('back/planning');
        }

        $this->model->create($data);
        $this->setFlash('Planning ajouté avec succès.');
        $this->redirect('back/planning');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id_planning'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('Identifiant planning invalide.');
            $this->redirect('back/planning');
        }

        $data = $this->validatedData();
        if ($data === null) {
            $this->redirect('back/planning');
        }

        $this->model->update($id, $data);
        $this->setFlash('Planning modifié avec succès.');
        $this->redirect('back/planning');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id_planning'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('Identifiant planning invalide.');
            $this->redirect('back/planning');
        }

        $this->model->delete($id);
        $this->setFlash('Planning supprimé avec succès.');
        $this->redirect('back/planning');
    }

    private function validatedData(): ?array
    {
        $nomFormation = $this->normalizeSpaces($_POST['nom_de_formation'] ?? '');
        $dateDebut = $this->normalizeSpaces($_POST['date_debut'] ?? '');
        $dateFin = $this->normalizeSpaces($_POST['date_fin'] ?? '');
        $type = $this->normalizeSpaces($_POST['TYPE'] ?? '');
        $minimumDate = (new \DateTimeImmutable('today'))->format('Y-m-d');

        if ($nomFormation === '' || $dateDebut === '' || $dateFin === '' || $type === '') {
            return null;
        }

        if (!$this->isValidText($nomFormation, 3, 120)) {
            return null;
        }

        if (!$this->isValidText($type, 2, 40)) {
            return null;
        }

        $start = \DateTimeImmutable::createFromFormat('Y-m-d', $dateDebut);
        $end = \DateTimeImmutable::createFromFormat('Y-m-d', $dateFin);

        if (!$start || !$end || $start->format('Y-m-d') !== $dateDebut || $end->format('Y-m-d') !== $dateFin) {
            return null;
        }

        if ($dateDebut <= $minimumDate) {
            return null;
        }

        if ($dateFin <= $dateDebut) {
            return null;
        }

        return [
            'nom_de_formation' => $nomFormation,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'TYPE' => $type,
        ];
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }

    private function isValidText(string $value, int $minLen, int $maxLen): bool
    {
        return mb_strlen($value) >= $minLen && mb_strlen($value) <= $maxLen && (bool) preg_match('/^[\p{L}\p{N}\s\'’\-.,()]+$/u', $value);
    }
}
