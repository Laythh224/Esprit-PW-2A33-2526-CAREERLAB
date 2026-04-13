<?php

namespace App\controllers;

use App\controllers\BaseController;
use App\models\PlanningOptionModel;

class BackPlanningOptionController extends BaseController
{
    private PlanningOptionModel $model;

    public function __construct()
    {
        $this->model = new PlanningOptionModel();
    }

    public function index(): void
    {
        $this->render('back/planning_option', [
            'links' => $this->model->all(),
            'planningChoices' => $this->model->planningChoices(),
            'optionChoices' => $this->model->optionChoices(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function store(): void
    {
        $idPlanning = (int) ($_POST['id_planning'] ?? 0);
        $idOption = (int) ($_POST['id_option'] ?? 0);

        if ($idPlanning <= 0 || $idOption <= 0) {
            $this->setFlash('Sélection invalide pour la liaison planning_option.');
            $this->redirect('back/planning-option');
        }

        if ($this->model->exists($idPlanning, $idOption)) {
            $this->setFlash('Cette liaison existe déjà.');
            $this->redirect('back/planning-option');
        }

        $this->model->create($idPlanning, $idOption);
        $this->setFlash('Liaison ajoutée avec succès.');
        $this->redirect('back/planning-option');
    }

    public function delete(): void
    {
        $idPlanning = (int) ($_POST['id_planning'] ?? 0);
        $idOption = (int) ($_POST['id_option'] ?? 0);

        if ($idPlanning <= 0 || $idOption <= 0) {
            $this->setFlash('Identifiants invalides pour la suppression.');
            $this->redirect('back/planning-option');
        }

        $this->model->delete($idPlanning, $idOption);
        $this->setFlash('Liaison supprimée avec succès.');
        $this->redirect('back/planning-option');
    }
}
