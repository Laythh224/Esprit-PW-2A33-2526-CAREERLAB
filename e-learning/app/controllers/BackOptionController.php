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
        $nomOption = trim($_POST['nom_option'] ?? '');
        $specialite = trim($_POST['specialite'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($nomOption === '') {
            $this->setFlash('nom_option est obligatoire.');
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
        $nomOption = trim($_POST['nom_option'] ?? '');
        $specialite = trim($_POST['specialite'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id <= 0 || $nomOption === '') {
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
}
