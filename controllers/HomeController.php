<?php

class HomeController
{
    public function index(): void
    {
        $this->renderView('indexF.view.php');
    }

    public function creerCompte(): void
    {
        $this->renderView('creer-compte.view.php');
    }

    private function renderView(string $viewFile): void
    {
        require_once __DIR__ . '/../Views/' . $viewFile;
    }
}

