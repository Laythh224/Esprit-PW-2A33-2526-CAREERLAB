<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;
use App\Model\ClientModel;
use App\Model\Database;
use App\Model\FormationModel;
use App\Model\PlatformDatabase;
use App\Model\SessionModel;
use App\Model\WhatsappService;
use PDO;

class InscriptionController extends BaseController
{
    private const DEFAULT_AGE = 25;

    private FormationModel $formationModel;
    private SessionModel $sessionModel;
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->formationModel = new FormationModel();
        $this->sessionModel = new SessionModel();
        $this->clientModel = new ClientModel();
    }

    public function form(): void
    {
        if (!$this->isPlatformUserLoggedIn()) {
            header('Location: ../index.php?page=login', true, 302);
            exit;
        }

        $nomFormation = $this->normalizeSpaces($_GET['nom_formation'] ?? '');
        $sessionId = (int) ($_GET['session_id'] ?? 0);

        if ($nomFormation === '' || !$this->formationModel->exists($nomFormation)) {
            $this->setFlash('Formation introuvable.');
            $this->redirect('front/formations');
        }

        if ($sessionId <= 0) {
            $this->setFlash('Choisissez une session avec des places disponibles.');
            $this->redirect('front/formations');
        }

        $session = $this->sessionModel->findById($sessionId);
        if ($session === null || (string) ($session['nom_formation'] ?? '') !== $nomFormation) {
            $this->setFlash('Session invalide pour cette formation.');
            $this->redirect('front/formations');
        }

        $nbPlace = (int) ($session['nb_place'] ?? 0);
        if ($nbPlace <= 0) {
            $this->setFlash('Plus de places disponibles pour cette session.');
            $this->redirect('front/formations');
        }

        $profile = $this->loadLoggedInProfile();
        if ($profile === null) {
            $this->setFlash('Aucun profil plateforme trouve pour ce compte. Utilisez un compte utilisateur, formateur ou entreprise.');
            $this->redirect('front/formations');
        }

        $preview = $this->buildPreview($profile);
        if ($preview === null) {
            $this->setFlash('Completez votre telephone (8 chiffres) dans votre profil sur le site avant de vous inscrire.');
            header('Location: ../index.php?page=profile', true, 302);
            exit;
        }

        $certUrls = $this->buildCertificatePreviewUrls($preview, $nomFormation, $sessionId);

        $this->render('FrontOffice/inscription', [
            'title' => 'Inscription',
            'active' => 'formations',
            'nomFormation' => $nomFormation,
            'sessionId' => $sessionId,
            'flash' => $this->getFlash(),
            'preview' => $preview,
            'certificateEmbedUrl' => $certUrls['embed'],
            'certificateFullUrl' => $certUrls['full'],
        ]);
    }

    public function submit(): void
    {
        if (!$this->isPlatformUserLoggedIn()) {
            header('Location: ../index.php?page=login', true, 302);
            exit;
        }

        $nomFormation = $this->normalizeSpaces($_POST['nom_formation'] ?? '');
        $sessionId = (int) ($_POST['session_id'] ?? 0);

        if ($nomFormation === '' || !$this->formationModel->exists($nomFormation)) {
            $this->setFlash('Formation introuvable.');
            $this->redirect('front/formations');
        }

        if ($sessionId <= 0) {
            $this->setFlash('Session invalide.');
            $this->redirect('front/formations');
        }

        $session = $this->sessionModel->findById($sessionId);
        if ($session === null || (string) ($session['nom_formation'] ?? '') !== $nomFormation) {
            $this->setFlash('Session invalide pour cette formation.');
            $this->redirect('front/formations');
        }

        $profile = $this->loadLoggedInProfile();
        if ($profile === null) {
            $this->setFlash('Profil introuvable.');
            $this->redirect('front/formations');
        }

        $data = $this->buildClientPayload($profile);
        if ($data === null) {
            $this->setFlash('Donnees profil invalides (telephone). Mettez a jour votre profil.');
            header('Location: ../index.php?page=profile', true, 302);
            exit;
        }

        $cin = $data['cin'];
        $existing = $this->clientModel->find($cin);

        if ($existing !== null) {
            $oldSid = (int) ($existing['session_id'] ?? 0);
            $oldForm = (string) ($existing['nom_formation'] ?? '');
            if ($oldSid === $sessionId && $oldForm === $nomFormation) {
                $this->setFlash('Vous etes deja inscrit a cette session.');
                $this->redirect('front/formations');
            }
        }

        $nbPlace = (int) ($session['nb_place'] ?? 0);
        if ($nbPlace <= 0) {
            $this->setFlash('Plus de places disponibles pour cette session.');
            $this->redirect('front/formations');
        }

        $data['nom_formation'] = $nomFormation;
        $data['session_id'] = $sessionId;

        $db = Database::connection();
        $db->beginTransaction();
        try {
            if ($existing !== null) {
                $oldSid = (int) ($existing['session_id'] ?? 0);
                if ($oldSid > 0 && $oldSid !== $sessionId) {
                    $this->sessionModel->incrementNbPlace($oldSid);
                }
            }

            if (!$this->sessionModel->decrementNbPlace($sessionId)) {
                $db->rollBack();
                $this->setFlash('Plus de places disponibles (complet).');
                $this->redirect('front/formations');
            }

            if ($existing === null) {
                $this->clientModel->create($data);
            } else {
                $this->clientModel->update($cin, $data);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            $this->setFlash('Erreur lors de l\'inscription. Reessayez.');
            $this->redirect('front/inscription', ['nom_formation' => $nomFormation, 'session_id' => (string) $sessionId]);
        }

        $whatsapp = new WhatsappService();
        $whatsappSuffix = $whatsapp->notifyInscription($data['tel'], $nomFormation, $this->participantLabelForWhatsapp($data));

        $this->setFlash('Inscription reussie pour la formation : ' . $nomFormation . '.' . $whatsappSuffix);
        $this->redirect('front/formations');
    }

    /**
     * Libellé pour les notifications WhatsApp : prénom + nom, ou raison sociale (entreprise).
     *
     * @param array<string, mixed> $data Payload client (create/update)
     */
    private function participantLabelForWhatsapp(array $data): string
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        if ($prenom === '' || $prenom === '-') {
            return $nom;
        }

        return trim($prenom . ' ' . $nom);
    }

    private function isPlatformUserLoggedIn(): bool
    {
        return !empty($_SESSION['is_logged_in']) && (int) ($_SESSION['user_id'] ?? 0) > 0;
    }

    /**
     * @return array{type: string, id: int, row: array}|null
     */
    private function loadLoggedInProfile(): ?array
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            return null;
        }

        $pdo = PlatformDatabase::connection();

        $stmt = $pdo->prepare('SELECT id, nom, prenom, email, telephone, niveau_etude FROM utilisateur WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false && $row !== null) {
            return ['type' => 'utilisateur', 'id' => $userId, 'row' => $row];
        }

        $stmt = $pdo->prepare('SELECT id, nom, prenom, email, telephone, specialite FROM formateur WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false && $row !== null) {
            return ['type' => 'formateur', 'id' => $userId, 'row' => $row];
        }

        $stmt = $pdo->prepare(
            'SELECT id, nom_entreprise, email, telephone, secteur FROM entreprise WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false && $row !== null) {
            return ['type' => 'entreprise', 'id' => $userId, 'row' => $row];
        }

        return null;
    }

    /**
     * @param array{type: string, id: int, row: array} $profile
     */
    private function buildPreview(array $profile): ?array
    {
        $payload = $this->buildClientPayload($profile);
        if ($payload === null) {
            return null;
        }

        $row = $profile['row'];
        if ($profile['type'] === 'entreprise') {
            return [
                'label' => 'Entreprise',
                'nom' => $this->truncate((string) ($row['nom_entreprise'] ?? ''), 80),
                'prenom' => '—',
                'email' => strtolower(trim((string) ($row['email'] ?? ''))),
                'telephone' => $payload['tel'],
                'niveau' => $this->truncate(trim((string) ($row['secteur'] ?? '')), 80) ?: '—',
                'age' => $payload['age'],
            ];
        }

        return [
            'label' => $profile['type'] === 'formateur' ? 'Formateur' : 'Utilisateur',
            'nom' => $payload['nom'],
            'prenom' => $payload['prenom'],
            'email' => $payload['adresse'],
            'telephone' => $payload['tel'],
            'niveau' => $payload['niveau'],
            'age' => $payload['age'],
        ];
    }

    /**
     * @param array{type: string, id: int, row: array} $profile
     * @return array{cin: string, nom: string, prenom: string, adresse: string, niveau: string, age: int, tel: string}|null
     */
    private function buildClientPayload(array $profile): ?array
    {
        $type = $profile['type'];
        $id = $profile['id'];
        $row = $profile['row'];

        $cin = $this->syntheticCin($type, $id);
        $tel = $this->normalizeTel8((string) ($row['telephone'] ?? ''));
        if ($tel === null) {
            return null;
        }

        if ($type === 'entreprise') {
            $nom = $this->truncate($this->normalizeSpaces((string) ($row['nom_entreprise'] ?? '')), 80);
            $prenom = '-';
            if ($nom === '') {
                return null;
            }
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return null;
            }
            $niveau = $this->truncate($this->normalizeSpaces((string) ($row['secteur'] ?? '')), 80);
            if ($niveau === '') {
                $niveau = '—';
            }

            return [
                'cin' => $cin,
                'nom' => $nom,
                'prenom' => $prenom,
                'adresse' => $email,
                'niveau' => $niveau,
                'age' => self::DEFAULT_AGE,
                'tel' => $tel,
            ];
        }

        $nom = $this->truncate($this->normalizeSpaces((string) ($row['nom'] ?? '')), 80);
        $prenom = $this->truncate($this->normalizeSpaces((string) ($row['prenom'] ?? '')), 80);
        if ($nom === '' || $prenom === '') {
            return null;
        }

        $email = strtolower(trim((string) ($row['email'] ?? '')));
        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return null;
        }

        $niveauRaw = $type === 'formateur'
            ? (string) ($row['specialite'] ?? '')
            : (string) ($row['niveau_etude'] ?? '');
        $niveau = $this->truncate($this->normalizeSpaces($niveauRaw), 80);
        if ($niveau === '') {
            $niveau = '—';
        }

        if (!preg_match("/^[\p{L}\s\-']+$/u", $nom) || !preg_match("/^[\p{L}\s\-']+$/u", $prenom)) {
            return null;
        }

        return [
            'cin' => $cin,
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $email,
            'niveau' => $niveau,
            'age' => self::DEFAULT_AGE,
            'tel' => $tel,
        ];
    }

    private function syntheticCin(string $accountType, int $id): string
    {
        return sprintf('%08d', abs(crc32($accountType . ':' . $id)) % 100000000);
    }

    private function normalizeTel8(string $raw): ?string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) >= 8) {
            return substr($digits, -8);
        }

        return str_pad($digits, 8, '0', STR_PAD_LEFT);
    }

    private function truncate(string $value, int $max): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, $max);
        }

        return substr($value, 0, $max);
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }

    /**
     * @param array<string, mixed> $preview
     * @return array{embed: string, full: string}
     */
    private function buildCertificatePreviewUrls(array $preview, string $nomFormation, int $sessionId): array
    {
        $recipient = $this->certificateRecipientDisplayName($preview);
        $uid = (int) ($_SESSION['user_id'] ?? 0);
        $certId = 'CL-' . strtoupper(substr(sha1($nomFormation . '|' . $sessionId . '|' . $uid . '|preview'), 0, 10));

        $base = [
            'r' => 'front/certificate',
            'nom_utilisateur' => $recipient,
            'nom_formation' => $nomFormation,
            'certificate_id' => $certId,
        ];

        return [
            'embed' => 'index.php?' . http_build_query(array_merge($base, ['embed' => '1'])),
            'full' => 'index.php?' . http_build_query($base),
        ];
    }

    /**
     * @param array<string, mixed> $preview
     */
    private function certificateRecipientDisplayName(array $preview): string
    {
        $prenom = trim((string) ($preview['prenom'] ?? ''));
        $nom = trim((string) ($preview['nom'] ?? ''));
        if ($prenom === '' || $prenom === '—') {
            return $nom !== '' ? $nom : 'Participant';
        }

        return trim($prenom . ' ' . $nom);
    }
}
