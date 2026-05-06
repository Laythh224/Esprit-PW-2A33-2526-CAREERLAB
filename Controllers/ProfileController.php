<?php

class ProfileController
{
    private UserModel $userModel;
    private FormateurModel $formateurModel;
    private EntrepriseModel $entrepriseModel;
    private PasswordService $passwordService;

    public function __construct(
        UserModel $userModel,
        FormateurModel $formateurModel,
        EntrepriseModel $entrepriseModel,
        PasswordService $passwordService
    ) {
        $this->userModel = $userModel;
        $this->formateurModel = $formateurModel;
        $this->entrepriseModel = $entrepriseModel;
        $this->passwordService = $passwordService;
    }

    public function index(): void
    {
        $this->ensureSessionStarted();

        if (empty($_SESSION['is_logged_in'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (($_SESSION['role'] ?? '') === 'admin') {
            header('Location: index.php?r=admin&view=dashboard');
            exit;
        }

        $context = $this->resolveAccountContext();
        if ($context === null) {
            header('Location: index.php?page=login');
            exit;
        }

        [$accountType, $account] = $context;

        $messages = [
            'success' => '',
            'error' => '',
        ];

        $errors = [
            'profile' => $this->createProfileErrors(),
            'password' => $this->createPasswordErrors(),
        ];

        $values = $this->buildProfileValues($accountType, $account);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = trim((string) ($_POST['action'] ?? ''));

            if ($action === 'update_profile') {
                $this->handleProfileUpdate($accountType, $account, $values, $errors, $messages);
            } elseif ($action === 'change_password') {
                $this->handlePasswordChange($accountType, $account, $errors, $messages);
            }

            $context = $this->resolveAccountContext();
            if ($context !== null) {
                [$accountType, $account] = $context;
                $values = $this->buildProfileValues($accountType, $account);
            }
        }

        $isVerified = $this->isAccountVerified($account);
        $roleLabel = $this->resolveRoleLabel($accountType);
        $navigationLinks = $this->buildNavigationLinks();
        $profileSummary = $this->buildProfileSummary($accountType, $account, $roleLabel, $isVerified);
        $infoCards = $this->buildInfoCards($accountType, $account, $isVerified);

        require_once __DIR__ . '/../Views/profile.view.php';
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function resolveAccountContext(): ?array
    {
        $sessionRole = strtolower(trim((string) ($_SESSION['role'] ?? '')));
        $sessionId = (int) ($_SESSION['user_id'] ?? 0);
        $email = trim((string) ($_SESSION['user_email'] ?? ''));

        if ($sessionRole === 'entreprise') {
            $account = $sessionId > 0
                ? $this->entrepriseModel->findById($sessionId)
                : $this->entrepriseModel->findByEmail($email);

            if ($account !== null) {
                return ['entreprise', $account];
            }
        }

        if ($sessionId > 0) {
            $formateur = $this->formateurModel->findById($sessionId);
            if ($formateur !== null && $sessionRole !== 'entreprise') {
                return ['formateur', $formateur];
            }

            $user = $this->userModel->findById($sessionId);
            if ($user !== null) {
                return ['utilisateur', $user];
            }
        }

        if ($email !== '') {
            $formateur = $this->formateurModel->findByEmail($email);
            if ($formateur !== null && $sessionRole !== 'entreprise') {
                return ['formateur', $formateur];
            }

            $user = $this->userModel->findByEmail($email);
            if ($user !== null) {
                return ['utilisateur', $user];
            }

            $entreprise = $this->entrepriseModel->findByEmail($email);
            if ($entreprise !== null) {
                return ['entreprise', $entreprise];
            }
        }

        return null;
    }

    private function createProfileErrors(): array
    {
        return [
            'nom' => '',
            'prenom' => '',
            'sexe' => '',
            'email' => '',
            'telephone' => '',
            'niveau_etude' => '',
            'domaine' => '',
            'competences' => '',
            'specialite' => '',
            'diplomes' => '',
            'experience' => '',
            'nom_entreprise' => '',
            'adresse' => '',
            'ville' => '',
            'secteur' => '',
            'description' => '',
            'site_web' => '',
        ];
    }

    private function createPasswordErrors(): array
    {
        return [
            'current_password' => '',
            'password' => '',
            'confirm_password' => '',
        ];
    }

    private function buildProfileValues(string $accountType, array $account): array
    {
        if ($accountType === 'entreprise') {
            return [
                'nom_entreprise' => (string) ($account['nom_entreprise'] ?? ''),
                'email' => (string) ($account['email'] ?? ''),
                'telephone' => (string) ($account['telephone'] ?? ''),
                'adresse' => (string) ($account['adresse'] ?? ''),
                'ville' => (string) ($account['ville'] ?? ''),
                'secteur' => (string) ($account['secteur'] ?? ''),
                'description' => (string) ($account['description'] ?? ''),
                'site_web' => (string) ($account['site_web'] ?? ''),
            ];
        }

        if ($accountType === 'formateur') {
            return [
                'nom' => (string) ($account['nom'] ?? ''),
                'prenom' => (string) ($account['prenom'] ?? ''),
                'sexe' => (string) ($account['sexe'] ?? ''),
                'email' => (string) ($account['email'] ?? ''),
                'telephone' => (string) ($account['telephone'] ?? ''),
                'specialite' => (string) ($account['specialite'] ?? ''),
                'diplomes' => (string) ($account['diplomes'] ?? ''),
                'experience' => (string) ($account['experience'] ?? ''),
            ];
        }

        return [
            'nom' => (string) ($account['nom'] ?? ''),
            'prenom' => (string) ($account['prenom'] ?? ''),
            'sexe' => (string) ($account['sexe'] ?? ''),
            'email' => (string) ($account['email'] ?? ''),
            'telephone' => (string) ($account['telephone'] ?? ''),
            'niveau_etude' => (string) ($account['niveau_etude'] ?? ''),
            'domaine' => (string) ($account['domaine'] ?? ''),
            'competences' => (string) ($account['competences'] ?? ''),
        ];
    }

    private function handleProfileUpdate(
        string $accountType,
        array $account,
        array &$values,
        array &$errors,
        array &$messages
    ): void {
        if ($accountType === 'entreprise') {
            $payload = [
                'id' => (int) ($account['id'] ?? 0),
                'nom_entreprise' => $this->cleanText((string) ($_POST['nom_entreprise'] ?? '')),
                'email' => $this->cleanEmail((string) ($_POST['email'] ?? '')),
                'telephone' => $this->cleanText((string) ($_POST['telephone'] ?? '')),
                'adresse' => $this->cleanText((string) ($_POST['adresse'] ?? '')),
                'ville' => $this->cleanText((string) ($_POST['ville'] ?? '')),
                'secteur' => $this->cleanText((string) ($_POST['secteur'] ?? '')),
                'description' => $this->cleanMultilineText((string) ($_POST['description'] ?? '')),
                'site_web' => $this->cleanText((string) ($_POST['site_web'] ?? '')),
            ];

            $values = array_merge($values, $payload);
            $this->validateEntreprisePayload($payload, $errors['profile']);

            if ($this->hasErrors($errors['profile'])) {
                $messages['error'] = 'Veuillez corriger les champs invalides.';
                return;
            }

            try {
                $this->entrepriseModel->update($payload);
                $this->refreshSessionAfterUpdate($accountType, (int) $payload['id']);
                $messages['success'] = 'Vos informations ont ete mises a jour.';
            } catch (Throwable $exception) {
                $messages['error'] = $exception->getMessage();
            }

            return;
        }

        if ($accountType === 'formateur') {
            $payload = [
                'id' => (int) ($account['id'] ?? 0),
                'nom' => $this->cleanText((string) ($_POST['nom'] ?? '')),
                'prenom' => $this->cleanText((string) ($_POST['prenom'] ?? '')),
                'sexe' => $this->cleanText((string) ($_POST['sexe'] ?? '')),
                'email' => $this->cleanEmail((string) ($_POST['email'] ?? '')),
                'telephone' => $this->cleanText((string) ($_POST['telephone'] ?? '')),
                'specialite' => $this->cleanText((string) ($_POST['specialite'] ?? '')),
                'diplomes' => $this->cleanText((string) ($_POST['diplomes'] ?? '')),
                'experience' => $this->cleanMultilineText((string) ($_POST['experience'] ?? '')),
            ];

            $values = array_merge($values, $payload);
            $this->validateUserPayload($payload, $errors['profile']);

            if ($this->hasErrors($errors['profile'])) {
                $messages['error'] = 'Veuillez corriger les champs invalides.';
                return;
            }

            try {
                $this->formateurModel->updateProfile($payload);
                $this->refreshSessionAfterUpdate($accountType, (int) $payload['id']);
                $messages['success'] = 'Vos informations ont ete mises a jour.';
            } catch (Throwable $exception) {
                $messages['error'] = $exception->getMessage();
            }

            return;
        }

        $payload = [
            'id' => (int) ($account['id'] ?? 0),
            'nom' => $this->cleanText((string) ($_POST['nom'] ?? '')),
            'prenom' => $this->cleanText((string) ($_POST['prenom'] ?? '')),
            'sexe' => $this->cleanText((string) ($_POST['sexe'] ?? '')),
            'email' => $this->cleanEmail((string) ($_POST['email'] ?? '')),
            'telephone' => $this->cleanText((string) ($_POST['telephone'] ?? '')),
            'niveau_etude' => $this->cleanText((string) ($_POST['niveau_etude'] ?? '')),
            'domaine' => $this->cleanText((string) ($_POST['domaine'] ?? '')),
            'competences' => $this->cleanMultilineText((string) ($_POST['competences'] ?? '')),
        ];

        $values = array_merge($values, $payload);
        $this->validateUserPayload($payload, $errors['profile']);

        if ($this->hasErrors($errors['profile'])) {
            $messages['error'] = 'Veuillez corriger les champs invalides.';
            return;
        }

        try {
            $this->userModel->update($payload);
            $this->refreshSessionAfterUpdate($accountType, (int) $payload['id']);
            $messages['success'] = 'Vos informations ont ete mises a jour.';
        } catch (Throwable $exception) {
            $messages['error'] = $exception->getMessage();
        }
    }

    private function handlePasswordChange(
        string $accountType,
        array $account,
        array &$errors,
        array &$messages
    ): void {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($currentPassword === '') {
            $errors['password']['current_password'] = 'Le mot de passe actuel est obligatoire.';
        } elseif (!$this->isMatchingPassword($currentPassword, (string) ($account['password'] ?? ''))) {
            $errors['password']['current_password'] = 'Le mot de passe actuel est incorrect.';
        }

        $passwordErrors = $this->passwordService->validatePassword($password, $confirmPassword);
        $errors['password']['password'] = $passwordErrors['password'];
        $errors['password']['confirm_password'] = $passwordErrors['confirm_password'];

        if ($this->hasErrors($errors['password'])) {
            $messages['error'] = 'Veuillez corriger les erreurs sur le mot de passe.';
            return;
        }

        $payload = $this->buildProfileValues($accountType, $account);
        $payload['id'] = (int) ($account['id'] ?? 0);
        $payload['password'] = $password;

        try {
            if ($accountType === 'entreprise') {
                $this->entrepriseModel->update($payload);
            } elseif ($accountType === 'formateur') {
                $this->formateurModel->updateProfile($payload);
            } else {
                $this->userModel->update($payload);
            }

            $this->refreshSessionAfterUpdate($accountType, (int) $payload['id']);
            $messages['success'] = 'Mot de passe mis a jour avec succes.';
        } catch (Throwable $exception) {
            $messages['error'] = $exception->getMessage();
        }
    }

    private function validateUserPayload(array $payload, array &$errors): void
    {
        if ($payload['nom'] === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if ($payload['prenom'] === '') {
            $errors['prenom'] = 'Le prenom est obligatoire.';
        }

        $sexeValue = mb_strtolower((string) ($payload['sexe'] ?? ''), 'UTF-8');
        if ($sexeValue === '') {
            $errors['sexe'] = 'Le sexe est obligatoire.';
        } elseif (!in_array($sexeValue, ['homme', 'femme'], true)) {
            $errors['sexe'] = 'Sexe invalide.';
        }

        if ($payload['email'] === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        }
    }

    private function validateEntreprisePayload(array $payload, array &$errors): void
    {
        if ($payload['nom_entreprise'] === '') {
            $errors['nom_entreprise'] = "Le nom de l'entreprise est obligatoire.";
        }

        if ($payload['email'] === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        }
    }

    private function buildNavigationLinks(): array
    {
        return [
            ['label' => 'Accueil', 'href' => 'index.php?r=main'],
            ['label' => 'Offres', 'href' => 'index.php?r=main#offres'],
            ['label' => 'Formations', 'href' => 'index.php?r=main#formations'],
            ['label' => 'Entreprises', 'href' => 'index.php?r=main#entreprises'],
            ['label' => 'Formateurs', 'href' => 'index.php?r=main#formateurs'],
            ['label' => 'Statistiques', 'href' => 'index.php?page=statistiques'],
            ['label' => 'Assistant IA', 'href' => 'index.php?r=main#assistant-ia'],
            ['label' => 'Profil', 'href' => 'index.php?page=profile', 'active' => true],
        ];
    }

    private function buildProfileSummary(string $accountType, array $account, string $roleLabel, bool $isVerified): array
    {
        $displayName = $accountType === 'entreprise'
            ? trim((string) ($account['nom_entreprise'] ?? ''))
            : trim((string) ($account['prenom'] ?? '') . ' ' . (string) ($account['nom'] ?? ''));

        if ($displayName === '') {
            $displayName = 'Votre profil';
        }

        $initials = '';
        foreach (preg_split('/\s+/', $displayName) as $part) {
            if ($part === '') {
                continue;
            }
            $initials .= mb_substr($part, 0, 1, 'UTF-8');
        }

        return [
            'display_name' => $displayName,
            'email' => (string) ($account['email'] ?? ''),
            'telephone' => (string) ($account['telephone'] ?? ''),
            'role_label' => $roleLabel,
            'initials' => mb_strtoupper(mb_substr($initials, 0, 2, 'UTF-8'), 'UTF-8'),
            'is_verified' => $isVerified,
            'created_at' => $this->formatDate((string) ($account['created_at'] ?? '')),
        ];
    }

    private function buildInfoCards(string $accountType, array $account, bool $isVerified): array
    {
        $cards = [];

        $cards[] = [
            'label' => 'Statut du compte',
            'value' => $isVerified ? 'Verifie' : 'Non verifie',
            'icon' => 'bi-shield-check',
            'accent' => $isVerified ? 'success' : 'warning',
        ];

        if (!empty($account['created_at'])) {
            $cards[] = [
                'label' => 'Date de creation',
                'value' => $this->formatDate((string) $account['created_at']),
                'icon' => 'bi-calendar3',
                'accent' => 'info',
            ];
        }

        if ($accountType !== 'entreprise' && !empty($account['sexe'])) {
            $cards[] = [
                'label' => 'Sexe / genre',
                'value' => $this->formatLabel((string) $account['sexe']),
                'icon' => 'bi-person-badge',
                'accent' => 'primary',
            ];
        }

        if ($accountType === 'utilisateur') {
            if (!empty($account['niveau_etude'])) {
                $cards[] = [
                    'label' => "Niveau d'etude",
                    'value' => (string) $account['niveau_etude'],
                    'icon' => 'bi-mortarboard',
                    'accent' => 'primary',
                ];
            }

            if (!empty($account['domaine'])) {
                $cards[] = [
                    'label' => 'Domaine',
                    'value' => (string) $account['domaine'],
                    'icon' => 'bi-briefcase',
                    'accent' => 'info',
                ];
            }

            if (!empty($account['cv'])) {
                $cards[] = [
                    'label' => 'CV',
                    'value' => 'Consulter le CV',
                    'href' => (string) $account['cv'],
                    'icon' => 'bi-file-earmark-pdf',
                    'accent' => 'violet',
                ];
            }
        } elseif ($accountType === 'formateur') {
            if (!empty($account['specialite'])) {
                $cards[] = [
                    'label' => 'Specialite',
                    'value' => (string) $account['specialite'],
                    'icon' => 'bi-lightbulb',
                    'accent' => 'info',
                ];
            }

            if (!empty($account['experience'])) {
                $cards[] = [
                    'label' => 'Experience',
                    'value' => $this->truncateText((string) $account['experience'], 90),
                    'icon' => 'bi-award',
                    'accent' => 'primary',
                ];
            }

            if (!empty($account['diplomes'])) {
                $cards[] = [
                    'label' => 'Diplome',
                    'value' => (string) $account['diplomes'],
                    'icon' => 'bi-patch-check',
                    'accent' => 'violet',
                ];
            }

            if (!empty($account['cv'])) {
                $cards[] = [
                    'label' => 'CV',
                    'value' => 'Consulter le CV',
                    'href' => (string) $account['cv'],
                    'icon' => 'bi-file-earmark-pdf',
                    'accent' => 'violet',
                ];
            }
        } else {
            if (!empty($account['secteur'])) {
                $cards[] = [
                    'label' => "Secteur d'activite",
                    'value' => (string) $account['secteur'],
                    'icon' => 'bi-buildings',
                    'accent' => 'info',
                ];
            }

            if (!empty($account['ville'])) {
                $cards[] = [
                    'label' => 'Ville',
                    'value' => (string) $account['ville'],
                    'icon' => 'bi-geo-alt',
                    'accent' => 'primary',
                ];
            }

            if (!empty($account['code_fiscal'])) {
                $cards[] = [
                    'label' => 'Code fiscal',
                    'value' => (string) $account['code_fiscal'],
                    'icon' => 'bi-receipt',
                    'accent' => 'violet',
                ];
            }

            if (!empty($account['site_web'])) {
                $cards[] = [
                    'label' => 'Site web',
                    'value' => 'Visiter le site',
                    'href' => (string) $account['site_web'],
                    'icon' => 'bi-globe2',
                    'accent' => 'violet',
                ];
            }
        }

        return $cards;
    }

    private function refreshSessionAfterUpdate(string $accountType, int $id): void
    {
        $account = $this->findAccountByTypeAndId($accountType, $id);
        if ($account === null) {
            return;
        }

        $_SESSION['role'] = $accountType;
        $_SESSION['user_id'] = $id;
        $_SESSION['user_email'] = (string) ($account['email'] ?? '');
        $_SESSION['account_verified'] = $this->isAccountVerified($account);
        $_SESSION['account_verified_at'] = $account['verified_at'] ?? null;
        $_SESSION['is_logged_in'] = true;

        if ($accountType === 'entreprise') {
            $_SESSION['user_name'] = (string) ($account['nom_entreprise'] ?? $account['email'] ?? 'Entreprise');
        } else {
            $_SESSION['user_name'] = trim((string) ($account['prenom'] ?? '') . ' ' . (string) ($account['nom'] ?? ''));
            if ($_SESSION['user_name'] === '') {
                $_SESSION['user_name'] = (string) ($account['email'] ?? 'Utilisateur');
            }
        }

        $_SESSION['nom'] = $_SESSION['user_name'];
    }

    private function findAccountByTypeAndId(string $accountType, int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if ($accountType === 'entreprise') {
            return $this->entrepriseModel->findById($id);
        }

        if ($accountType === 'formateur') {
            return $this->formateurModel->findById($id);
        }

        return $this->userModel->findById($id);
    }

    private function isMatchingPassword(string $plainPassword, string $storedPassword): bool
    {
        return password_verify($plainPassword, $storedPassword)
            || hash_equals($storedPassword, $plainPassword);
    }

    private function hasErrors(array $errors): bool
    {
        foreach ($errors as $error) {
            if ($error !== '') {
                return true;
            }
        }

        return false;
    }

    private function isAccountVerified(array $account): bool
    {
        return (int) ($account['verified'] ?? $account['email_verified'] ?? 0) === 1;
    }

    private function resolveRoleLabel(string $accountType): string
    {
        if ($accountType === 'formateur') {
            return 'Formateur';
        }

        if ($accountType === 'entreprise') {
            return 'Entreprise';
        }

        return 'Utilisateur';
    }

    private function cleanText(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function cleanMultilineText(string $value): string
    {
        return trim(strip_tags(str_replace(["\r\n", "\r"], "\n", $value)));
    }

    private function cleanEmail(string $value): string
    {
        return trim(mb_strtolower(strip_tags($value), 'UTF-8'));
    }

    private function formatDate(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }

        return date('d/m/Y', $timestamp);
    }

    private function truncateText(string $value, int $maxLength): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (mb_strlen($value, 'UTF-8') <= $maxLength) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $maxLength, 'UTF-8')) . '...';
    }

    private function formatLabel(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        return mb_strtoupper(mb_substr($value, 0, 1, 'UTF-8'), 'UTF-8')
            . mb_substr($value, 1, null, 'UTF-8');
    }
}
