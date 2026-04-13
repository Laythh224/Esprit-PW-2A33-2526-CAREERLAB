<?php
require_once __DIR__ . '/BaseController.php';

class PageController extends BaseController {

    public function home(): void {
        $this->render('pages/home', [
            'action' => 'home',
            'title'  => 'Career Lab - Accueil'
        ]);
    }

    public function about(): void {
        $this->render('pages/about', [
            'action' => 'about',
            'title'  => 'À propos - Career Lab'
        ]);
    }

    public function contact(): void {
        $this->render('pages/contact', [
            'action' => 'contact',
            'title'  => 'Contact - Career Lab'
        ]);
    }

    public function feature(): void {
        $this->render('pages/feature', [
            'action' => 'feature',
            'title'  => 'Métiers - Career Lab'
        ]);
    }
    
    public function team(): void {
        $this->render('pages/team', [
            'action' => 'team',
            'title'  => 'Evaluation - Career Lab'
        ]);
    }
    
    public function price(): void {
        $this->render('pages/price', [
            'action' => 'price',
            'title'  => 'Utilisateurs - Career Lab'
        ]);
    }
    
    public function blog(): void {
        $this->render('pages/blog', [
            'action' => 'blog',
            'title'  => 'Blog - Career Lab'
        ]);
    }

    public function detail(): void {
        $this->render('pages/detail', [
            'action' => 'detail',
            'title'  => 'Blog Detail - Career Lab'
        ]);
    }

    public function quote(): void {
        $this->render('pages/quote', [
            'action' => 'quote',
            'title'  => 'E_learning - Career Lab'
        ]);
    }
}
