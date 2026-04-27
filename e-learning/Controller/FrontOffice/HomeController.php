<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->redirect('front/formations');
    }
}

