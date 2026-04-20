<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SiteModel;

final class SiteController extends Controller
{
    /** @var array<string, string> */
    private const LEGACY_PAGES = [
        'dashboard' => 'legacy/index',
        'metiers' => 'legacy/icon-menu',
        'icon-menu' => 'legacy/icon-menu',
        'sidebar-style-2' => 'legacy/sidebar-style-2',
        'starter-template' => 'legacy/starter-template',
        'widgets' => 'legacy/widgets',

        'users' => 'legacy/components/avatars',
        'avatars' => 'legacy/components/avatars',
        'buttons' => 'legacy/components/buttons',
        'font-awesome-icons' => 'legacy/components/font-awesome-icons',
        'gridsystem' => 'legacy/components/gridsystem',
        'notifications' => 'legacy/components/notifications',
        'panels' => 'legacy/components/panels',
        'simple-line-icons' => 'legacy/components/simple-line-icons',
        'sweetalert' => 'legacy/components/sweetalert',
        'typography' => 'legacy/components/typography',

        'evaluation' => 'legacy/forms/forms',
        'forms' => 'legacy/forms/forms',

        'offres' => 'legacy/tables/tables',
        'tables' => 'legacy/tables/tables',
        'datatables' => 'legacy/tables/datatables',

        'elearning' => 'legacy/maps/googlemaps',
        'googlemaps' => 'legacy/maps/googlemaps',
        'jsvectormap' => 'legacy/maps/jsvectormap',

        'blog-dashboard' => 'legacy/charts/charts',
        'charts' => 'legacy/charts/charts',
        'sparkline' => 'legacy/charts/sparkline',

        'front' => 'legacy/indexF',
    ];

    public function __construct(private SiteModel $siteModel)
    {
    }

    public function home(): void
    {
        $this->legacy('dashboard');
    }

    /**
     * @return array<string, string>
     */
    public function getLegacyPages(): array
    {
        return self::LEGACY_PAGES;
    }

    public function legacy(string $routeName): void
    {
        $legacyViewPath = self::LEGACY_PAGES[$routeName] ?? null;

        if ($legacyViewPath === null) {
            $this->notFound();
            return;
        }

        $this->renderStandalone($legacyViewPath);
    }

    public function about(): void
    {
        $this->render('about', array_merge(
            $this->baseViewData('about', 'A propos - Career Lab', 'A propos', 'A propos'),
            [
                'about' => $this->siteModel->getAboutContent(),
                'teamHtml' => $this->renderTeam($this->siteModel->getTeamMembers()),
            ]
        ));
    }

    public function services(): void
    {
        $this->render('services', array_merge(
            $this->baseViewData('services', 'Services - Career Lab', 'Services', 'Services'),
            [
                'servicesHtml' => $this->renderServices($this->siteModel->getServices()),
            ]
        ));
    }

    public function team(): void
    {
        $this->render('team', array_merge(
            $this->baseViewData('team', 'Equipe - Career Lab', 'Notre equipe', 'Equipe'),
            [
                'teamHtml' => $this->renderTeam($this->siteModel->getTeamMembers()),
            ]
        ));
    }

    public function contact(): void
    {
        $flashMessage = $_SESSION['flash_message'] ?? null;
        $flashType = $_SESSION['flash_type'] ?? 'success';

        unset($_SESSION['flash_message'], $_SESSION['flash_type']);

        $this->render('contact', array_merge(
            $this->baseViewData('contact', 'Contact - Career Lab', 'Contact', 'Contact'),
            [
                'flashMessage' => $flashMessage,
                'flashType' => $flashType,
            ]
        ));
    }

    public function submitContact(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $subject = trim((string) ($_POST['subject'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));

        if ($name === '' || $email === '' || $subject === '' || $message === '') {
            $_SESSION['flash_message'] = 'Merci de remplir tous les champs du formulaire.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_message'] = 'Adresse email invalide.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('contact');
        }

        $_SESSION['flash_message'] = 'Votre message a ete envoye avec succes.';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('contact');
    }

    public function notFound(): void
    {
        $this->render('not-found', array_merge(
            $this->baseViewData('not-found', 'Page introuvable - Career Lab', '404', 'Page introuvable'),
            []
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function baseViewData(string $currentRoute, string $metaTitle, string $pageTitle = '', string $pageSubtitle = ''): array
    {
        $company = $this->siteModel->getCompanyInfo();

        return [
            'metaTitle' => $metaTitle,
            'company' => $company,
            'pageTitle' => $pageTitle,
            'pageSubtitle' => $pageSubtitle,
            'navHomeClass' => $currentRoute === 'home' ? 'active' : '',
            'navAboutClass' => $currentRoute === 'about' ? 'active' : '',
            'navServicesClass' => $currentRoute === 'services' ? 'active' : '',
            'navTeamClass' => $currentRoute === 'team' ? 'active' : '',
            'navContactClass' => $currentRoute === 'contact' ? 'active' : '',
        ];
    }

    /**
     * @param array<int, array<string, string>> $services
     */
    private function renderServices(array $services, ?int $limit = null): string
    {
        $html = '';
        $items = $limit === null ? $services : array_slice($services, 0, $limit);

        foreach ($items as $index => $service) {
            $delay = '0.' . (($index * 3) + 1) . 's';
            $html .= '<div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="' . $delay . '">';
            $html .= '<div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">';
            $html .= '<div class="service-icon"><i class="fa ' . $this->e($service['icon']) . ' text-white"></i></div>';
            $html .= '<h4 class="mb-3">' . $this->e($service['title']) . '</h4>';
            $html .= '<p class="m-0">' . $this->e($service['description']) . '</p>';
            $html .= '<a class="btn btn-lg btn-primary rounded" href="index.php?route=contact"><i class="bi bi-arrow-right"></i></a>';
            $html .= '</div></div>';
        }

        return $html;
    }

    /**
     * @param array<int, array<string, string>> $members
     */
    private function renderTeam(array $members): string
    {
        $html = '';

        foreach ($members as $index => $member) {
            $delay = '0.' . (($index * 3) + 1) . 's';
            $html .= '<div class="col-lg-4 wow slideInUp" data-wow-delay="' . $delay . '">';
            $html .= '<div class="team-item bg-light rounded overflow-hidden">';
            $html .= '<div class="team-img position-relative overflow-hidden">';
            $html .= '<img class="img-fluid w-100" src="' . $this->e($member['image']) . '" alt="' . $this->e($member['name']) . '">';
            $html .= '</div>';
            $html .= '<div class="text-center py-4">';
            $html .= '<h4 class="text-primary">' . $this->e($member['name']) . '</h4>';
            $html .= '<p class="text-uppercase m-0">' . $this->e($member['role']) . '</p>';
            $html .= '</div></div></div>';
        }

        return $html;
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
