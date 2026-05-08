<?php

class EvaluationController {
    public function index() {
        // Bridge vers le module projet_web
        $_GET['route'] = 'evaluation';
        require_once __DIR__ . '/../projet_web/index.php';
    }
}
