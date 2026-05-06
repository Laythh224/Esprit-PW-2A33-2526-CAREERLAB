<?php

require_once __DIR__ . '/../models/Metier.php';

class MetiersController
{
    private Metier $metierModel;

    public function __construct(PDO $connection)
    {
        $this->metierModel = new Metier($connection);
    }

    public function index(): void
    {
        $metiers = $this->metierModel->getAll();

        require_once __DIR__ . '/../views/metiers.php';
    }
}
