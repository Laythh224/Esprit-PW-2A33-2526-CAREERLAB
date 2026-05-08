<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;

class CertificateController extends BaseController
{
    /** Libellé affiché à la place d'une date (remise après la formation). */
    private const DELIVRANCE_FR = 'Après la fin de la formation';

    public function show(): void
    {
        $data = $this->collectCertificateData();
        $data['isPdf'] = false;
        $data['inlineCertificateCss'] = null;
        $data['logoSrc'] = $this->publicLogoUrl();
        $this->outputCertificate($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function outputCertificate(array $data): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../View/FrontOffice/certificate.php';
    }

    /**
     * @return array<string, mixed>
     */
    private function collectCertificateData(): array
    {
        $nomUtilisateur = $this->cleanText($_GET['nom_utilisateur'] ?? $_GET['u'] ?? '');
        $nomFormation = $this->cleanText($_GET['nom_formation'] ?? $_GET['f'] ?? '');
        $dateCompletion = $this->cleanText($_GET['date_completion'] ?? $_GET['d'] ?? '');
        $certificateId = $this->cleanText($_GET['certificate_id'] ?? $_GET['id'] ?? '');

        if ($nomUtilisateur === '') {
            $nomUtilisateur = 'Marie Dupont';
        }
        if ($nomFormation === '') {
            $nomFormation = 'Gestion de projet avancée';
        }
        if ($dateCompletion === '') {
            $dateCompletion = self::DELIVRANCE_FR;
        }
        if ($certificateId === '') {
            $certificateId = 'CL-' . strtoupper(substr(sha1((string) microtime(true)), 0, 10));
        }

        $instructorName = $this->cleanText($_GET['instructor'] ?? '') ?: '________________________';
        $directorName = $this->cleanText($_GET['director'] ?? '') ?: '________________________';

        $bodySentence = sprintf(
            'Le présent certificat atteste que %s a suivi la formation « %s ». La remise officielle interviendra après la fin de la formation.',
            $nomUtilisateur,
            $nomFormation
        );

        $certificateEmbed = isset($_GET['embed']) && (string) $_GET['embed'] === '1';

        return [
            'nomUtilisateur' => $nomUtilisateur,
            'nomFormation' => $nomFormation,
            'dateCompletion' => $dateCompletion,
            'certificateId' => $certificateId,
            'instructorName' => $instructorName,
            'directorName' => $directorName,
            'bodySentence' => $bodySentence,
            'certificateEmbed' => $certificateEmbed,
        ];
    }

    private function cleanText(mixed $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', (string) $value) ?? '');
    }

    private function publicLogoUrl(): string
    {
        if (!defined('E_LEARNING_WEB_BASE')) {
            return '';
        }

        return E_LEARNING_WEB_BASE . '/View/assets/img/careerlab-logo.png';
    }
}
