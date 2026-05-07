<?php

class OffresController {
    public function index() {
        $_GET['action'] = 'offres';
        $this->loadModule();
    }

    public function details() {
        $_GET['action'] = 'viewOffre';
        $this->loadModule();
    }

    public function postuler() {
        $_GET['action'] = 'apply';
        $this->loadModule();
    }

    public function adminList() {
        // Rediriger vers admin.php du module
        require __DIR__ . '/../projet wweb/admin.php';
    }

    public function store() {
        $_GET['action'] = 'publish';
        $this->loadModule();
    }

    public function delete() {
        $_GET['action'] = 'delete';
        $this->loadModule();
    }

    private function loadModule() {
        // Bridge vers le module existant
        require_once __DIR__ . '/../projet wweb/index.php';
    }
}
