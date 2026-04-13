<?php

namespace App\controllers;

use App\controllers\BaseController;
use App\models\OptionModel;

class FrontOptionController extends BaseController
{
    private OptionModel $optionModel;

    public function __construct()
    {
        $this->optionModel = new OptionModel();
    }

    public function index(): void
    {
        $this->render('front/options', [
            'options' => $this->optionModel->all(),
        ]);
    }
}
