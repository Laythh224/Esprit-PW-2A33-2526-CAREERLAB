<?php

namespace App\controllers;

use App\controllers\BaseController;
use App\models\OptionModel;

class BackOptionController extends BaseController
{
    private OptionModel $model;

    public function __construct()
    {
        $this->model = new OptionModel();
    }

    public function index(): void
    {
        $this->render('back/options', [
            'options' => $this->model->all(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function store(): void
    {
        $nomOption = $this->normalizeSpaces($_POST['nom_option'] ?? '');
        $specialite = $this->normalizeSpaces($_POST['specialite'] ?? '');
        $description = $this->normalizeSpaces($_POST['description'] ?? '');

        $validationError = $this->validateOptionFields($nomOption, $specialite, $description);
        if ($validationError !== null) {
            $this->setFlash($validationError);
            $this->redirect('back/options');
        }

        $this->model->create([
            'nom_option' => $nomOption,
            'specialite' => $specialite,
            'description' => $description,
        ]);

        $this->setFlash('Option ajoutée avec succès.');
        $this->redirect('back/options');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id_option'] ?? 0);
        $nomOption = $this->normalizeSpaces($_POST['nom_option'] ?? '');
        $specialite = $this->normalizeSpaces($_POST['specialite'] ?? '');
        $description = $this->normalizeSpaces($_POST['description'] ?? '');

        $validationError = $this->validateOptionFields($nomOption, $specialite, $description);
        if ($id <= 0 || $validationError !== null) {
            $this->setFlash('Données invalides pour la modification.');
            $this->redirect('back/options');
        }

        $this->model->update($id, [
            'nom_option' => $nomOption,
            'specialite' => $specialite,
            'description' => $description,
        ]);

        $this->setFlash('Option modifiée avec succès.');
        $this->redirect('back/options');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id_option'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('Identifiant invalide pour la suppression.');
            $this->redirect('back/options');
        }

        $this->model->delete($id);
        $this->setFlash('Option supprimée avec succès.');
        $this->redirect('back/options');
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }

    private function isValidText(string $value, int $minLen, int $maxLen): bool
    {
        return mb_strlen($value) >= $minLen && mb_strlen($value) <= $maxLen && (bool) preg_match('/^[\p{L}\p{N}\s\'’\-.,()]+$/u', $value);
    }

    private function validateOptionFields(string $nomOption, string $specialite, string $description): ?string
    {
        if ($nomOption === '') {
            return 'Le champ nom_option est obligatoire.';
        }

        if ($specialite === '') {
            return 'Le champ specialite est obligatoire.';
        }

        if ($description === '') {
            return 'Le champ description est obligatoire.';
        }

        if (!$this->isValidText($nomOption, 2, 80)) {
            return 'nom_option doit contenir 2 à 80 caractères valides.';
        }

        if (!$this->isValidText($specialite, 2, 80)) {
            return 'specialite doit contenir 2 à 80 caractères valides.';
        }

        if (mb_strlen($description) < 5 || mb_strlen($description) > 300) {
            return 'description doit contenir 5 à 300 caractères.';
        }

        if (!$this->isValidText($description, 5, 300)) {
            return 'description contient des caractères non valides.';
        }

        return null;
    }
}
