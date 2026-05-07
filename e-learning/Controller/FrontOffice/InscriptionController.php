<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;
use App\Model\ClientModel;
use App\Model\Database;
use App\Model\FormationModel;
use App\Model\SessionModel;
use App\Model\WhatsappService;

class InscriptionController extends BaseController
{
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

        $this->render('FrontOffice/inscription', [
            'title' => 'Inscription',
            'active' => 'formations',
            'nomFormation' => $nomFormation,
            'sessionId' => $sessionId,
            'flash' => $this->getFlash(),
            'oldInput' => $this->getOldInput(),
        ]);
    }

    public function submit(): void
    {
        $nomFormation = $this->normalizeSpaces($_POST['nom_formation'] ?? '');
        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $postedInput = $this->extractPostedInput();

        if ($nomFormation === '' || !$this->formationModel->exists($nomFormation)) {
            $this->setFlash('Formation introuvable.');
            $this->redirect('front/formations');
        }

        if ($sessionId <= 0) {
            $this->setOldInput($postedInput);
            $this->setFlash('Session invalide.');
            $this->redirect('front/formations');
        }

        $session = $this->sessionModel->findById($sessionId);
        if ($session === null || (string) ($session['nom_formation'] ?? '') !== $nomFormation) {
            $this->setOldInput($postedInput);
            $this->setFlash('Session invalide pour cette formation.');
            $this->redirect('front/formations');
        }

        $data = $this->validatedClientData();
        if ($data === null) {
            $this->setOldInput($postedInput);
            $this->setFlash('Donnees invalides. Verifiez le CIN (8 chiffres), l\'e-mail, le telephone (8 chiffres), l\'age (16-99) et les noms.');
            $this->redirect('front/inscription', ['nom_formation' => $nomFormation, 'session_id' => (string) $sessionId]);
        }

        if ($this->clientModel->exists($data['cin'])) {
            $this->setOldInput($postedInput);
            $this->setFlash('Ce CIN est deja enregistre.');
            $this->redirect('front/inscription', ['nom_formation' => $nomFormation, 'session_id' => (string) $sessionId]);
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
            $this->clientModel->create($data);
            if (!$this->sessionModel->decrementNbPlace($sessionId)) {
                $db->rollBack();
                $this->setFlash('Plus de places disponibles (complet).');
                $this->redirect('front/formations');
            }
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            $this->setOldInput($postedInput);
            $this->setFlash('Erreur lors de l\'inscription. Reessayez.');
            $this->redirect('front/inscription', ['nom_formation' => $nomFormation, 'session_id' => (string) $sessionId]);
        }

        $whatsapp = new WhatsappService();
        $whatsappSuffix = $whatsapp->notifyInscription($data['tel'], $nomFormation);

        $this->setFlash('Inscription reussie pour la formation : ' . $nomFormation . '.' . $whatsappSuffix);
        $this->redirect('front/formations');
    }

    private function validatedClientData(): ?array
    {
        $cin = $this->normalizeSpaces($_POST['cin'] ?? '');
        $nom = $this->normalizeSpaces($_POST['nom'] ?? '');
        $prenom = $this->normalizeSpaces($_POST['prenom'] ?? '');
        $adresse = $this->normalizeSpaces($_POST['adresse'] ?? '');
        $niveau = $this->normalizeSpaces($_POST['niveau'] ?? '');
        $tel = $this->normalizeSpaces($_POST['tel'] ?? '');
        $age = (int) ($_POST['age'] ?? 0);

        if ($cin === '' || $nom === '' || $prenom === '' || $adresse === '' || $niveau === '' || $tel === '') {
            return null;
        }

        if (!preg_match('/^\d{8}$/', $cin) || !preg_match('/^\d{8}$/', $tel)) {
            return null;
        }

        if ($age < 16 || $age > 99) {
            return null;
        }

        if (!preg_match("/^[\p{L}\s\-']+$/u", $nom) || !preg_match("/^[\p{L}\s\-']+$/u", $prenom)) {
            return null;
        }

        if (filter_var($adresse, FILTER_VALIDATE_EMAIL) === false) {
            return null;
        }

        $adresse = strtolower($adresse);

        return [
            'cin' => $cin,
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'niveau' => $niveau,
            'age' => $age,
            'tel' => $tel,
        ];
    }

    private function extractPostedInput(): array
    {
        return [
            'session_id' => (string) (int) ($_POST['session_id'] ?? 0),
            'nom_formation' => $this->normalizeSpaces($_POST['nom_formation'] ?? ''),
            'cin' => $this->normalizeSpaces($_POST['cin'] ?? ''),
            'nom' => $this->normalizeSpaces($_POST['nom'] ?? ''),
            'prenom' => $this->normalizeSpaces($_POST['prenom'] ?? ''),
            'adresse' => $this->normalizeSpaces($_POST['adresse'] ?? ''),
            'niveau' => $this->normalizeSpaces($_POST['niveau'] ?? ''),
            'age' => (string) ($_POST['age'] ?? ''),
            'tel' => $this->normalizeSpaces($_POST['tel'] ?? ''),
        ];
    }

    private function normalizeSpaces(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }
}
