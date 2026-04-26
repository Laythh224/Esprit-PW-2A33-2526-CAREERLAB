<?php
require_once __DIR__ . '/BaseController.php';

class PageController extends BaseController {

    public function home(): void { $this->render('pages/home'); }
    public function price(): void { $this->render('pages/price'); }
    public function feature(): void { $this->render('pages/feature'); }
    public function team(): void { $this->render('pages/team'); }
    public function quote(): void { $this->render('pages/quote'); }
    public function about(): void { $this->render('pages/about'); }
    public function contact(): void { $this->render('pages/contact'); }
}
