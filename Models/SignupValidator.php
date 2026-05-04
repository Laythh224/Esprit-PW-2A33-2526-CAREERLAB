<?php

class SignupValidator
{
    private PDO $conn;
    private PhoneNumberValidator $phoneValidator;
    private CvService $cvUploadService;

    public function __construct(PDO $conn, ?PhoneNumberValidator $phoneValidator = null, ?CvService $cvUploadService = null)
    {
        $this->conn = $conn;
        $this->phoneValidator = $phoneValidator ?? new PhoneNumberValidator();
        $this->cvUploadService = $cvUploadService ?? new CvService();
    }

    public function cleanText(string $value): string
    {
        return trim(strip_tags($value));
    }

    public function cleanMultilineText(string $value): string
    {
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        return trim(strip_tags($value));
    }

    public function cleanEmail(string $value): string
    {
        return trim(mb_strtolower($value, 'UTF-8'));
    }

    public function normalisePhone(string $value): array
    {
        return $this->phoneValidator->validate($value);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT 1
             FROM (
                SELECT email FROM utilisateur WHERE email = ?
                UNION ALL
                SELECT email FROM formateur WHERE email = ?
                UNION ALL
                SELECT email FROM entreprise WHERE email = ?
             ) AS comptes
             LIMIT 1'
        );
        $stmt->execute([$email, $email, $email]);

        return $stmt->fetchColumn() !== false;
    }

    public function validateEntreprise(EntrepriseEntity $entity, array $files): array
    {
        $errors = [
            'nom' => '',
            'email' => '',
            'telephone' => '',
            'cv' => '',
            'code_fiscal' => '',
            'adresse' => '',
            'ville' => '',
            'secteur' => '',
            'description' => '',
            'site' => '',
            'password' => '',
            'confirm_password' => '',
        ];

        if ($entity->getNom() === '') {
            $errors['nom'] = "Le nom de l'entreprise est obligatoire.";
        } elseif (!preg_match('/^[\p{L}\d][\p{L}\d\s\-_]{2,}$/u', $entity->getNom())) {
            $errors['nom'] = "Le nom de l'entreprise doit contenir au moins 3 caracteres (lettres et chiffres autorises).";
        }

        $errors['email'] = $this->validateEmail($entity->getEmail());

        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            $errors['telephone'] = $phoneValidation['error'];
        }

        $errors['password'] = $this->validatePassword($entity->getPassword());

        if ($entity->getConfirmPassword() === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($entity->getConfirmPassword() !== $entity->getPassword()) {
            $errors['confirm_password'] = 'La confirmation du mot de passe doit etre identique.';
        }

        $cvValidation = $this->validatePdfFile($files['cv'] ?? null, 'Le CV');
        if (!$cvValidation['ok']) {
            $errors['cv'] = $cvValidation['message'];
        }

        if ($entity->getCodeFiscal() === '') {
            $errors['code_fiscal'] = 'Le code fiscal est obligatoire.';
        } elseif (!preg_match('/^[A-Za-z0-9-]{6,20}$/', $entity->getCodeFiscal())) {
            $errors['code_fiscal'] = 'Le code fiscal doit etre alphanumerique (6 a 20 caracteres).';
        }

        if ($entity->getAdresse() === '') {
            $errors['adresse'] = "L'adresse est obligatoire.";
        }

        if ($entity->getVille() === '') {
            $errors['ville'] = 'La ville est obligatoire.';
        } elseif (!preg_match('/^[\p{L}\s\'-]{2,}$/u', $entity->getVille())) {
            $errors['ville'] = 'La ville doit contenir uniquement des lettres.';
        }

        if ($entity->getSecteur() === '') {
            $errors['secteur'] = "Le secteur d'activite est obligatoire.";
        }

        if ($entity->getDescription() === '') {
            $errors['description'] = 'La description est obligatoire.';
        } elseif (mb_strlen($entity->getDescription(), 'UTF-8') < 20) {
            $errors['description'] = 'La description doit contenir au moins 20 caracteres.';
        }

        if ($entity->getSite() !== '') {
            if (!filter_var($entity->getSite(), FILTER_VALIDATE_URL)) {
                $errors['site'] = 'Le site web doit etre une URL valide.';
            } elseif (!str_starts_with(mb_strtolower($entity->getSite(), 'UTF-8'), 'https://')) {
                $errors['site'] = 'Le site web doit commencer par https://';
            }
        }

        return $errors;
    }

    public function validateUtilisateur(UserEntity $entity, array $files): array
    {
        $errors = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'telephone' => '',
            'password' => '',
            'confirm_password' => '',
            'niveau' => '',
            'domaine' => '',
            'cv' => '',
        ];

        if ($entity->getNom() === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        } elseif (!preg_match('/^[\p{L}\s\'-]{2,}$/u', $entity->getNom())) {
            $errors['nom'] = 'Le nom doit contenir uniquement des lettres.';
        }

        if ($entity->getPrenom() === '') {
            $errors['prenom'] = 'Le prenom est obligatoire.';
        } elseif (!preg_match('/^[\p{L}\s\'-]{2,}$/u', $entity->getPrenom())) {
            $errors['prenom'] = 'Le prenom doit contenir uniquement des lettres.';
        }

        $errors['email'] = $this->validateEmail($entity->getEmail());

        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            $errors['telephone'] = $phoneValidation['error'];
        }

        $errors['password'] = $this->validatePassword($entity->getPassword());

        if ($entity->getConfirmPassword() === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($entity->getConfirmPassword() !== $entity->getPassword()) {
            $errors['confirm_password'] = 'La confirmation du mot de passe doit etre identique.';
        }

        if ($entity->getNiveau() === '') {
            $errors['niveau'] = "Le niveau d'etude est obligatoire.";
        }

        if ($entity->getDomaine() === '') {
            $errors['domaine'] = 'Le domaine est obligatoire.';
        }

        $cvValidation = $this->validatePdfFile($files['cv'] ?? null, 'Le CV');
        if (!$cvValidation['ok']) {
            $errors['cv'] = $cvValidation['message'];
        }

        return $errors;
    }

    public function validateFormateur(FormateurEntity $entity, array $files): array
    {
        $errors = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'telephone' => '',
            'password' => '',
            'confirm_password' => '',
            'specialite' => '',
            'diplomes' => '',
            'experience' => '',
            'cv' => '',
        ];

        if ($entity->getNom() === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        } elseif (!preg_match('/^[\p{L}\s\'-]{2,}$/u', $entity->getNom())) {
            $errors['nom'] = 'Le nom doit contenir uniquement des lettres.';
        }

        if ($entity->getPrenom() === '') {
            $errors['prenom'] = 'Le prenom est obligatoire.';
        } elseif (!preg_match('/^[\p{L}\s\'-]{2,}$/u', $entity->getPrenom())) {
            $errors['prenom'] = 'Le prenom doit contenir uniquement des lettres.';
        }

        $errors['email'] = $this->validateEmail($entity->getEmail());

        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            $errors['telephone'] = $phoneValidation['error'];
        }

        $errors['password'] = $this->validatePassword($entity->getPassword());

        if ($entity->getConfirmPassword() === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($entity->getConfirmPassword() !== $entity->getPassword()) {
            $errors['confirm_password'] = 'La confirmation du mot de passe doit etre identique.';
        }

        if ($entity->getSpecialite() === '') {
            $errors['specialite'] = 'La specialite est obligatoire.';
        } elseif (mb_strlen($entity->getSpecialite(), 'UTF-8') < 3) {
            $errors['specialite'] = 'La specialite doit contenir au moins 3 caracteres.';
        }

        if ($entity->getDiplomeCount() <= 0) {
            $errors['diplomes'] = 'Le nombre de diplomes doit etre un entier positif.';
        }

        if ($errors['diplomes'] === '') {
            $diplomeValidation = $this->validateDiplomaFiles($files['diplome_files'] ?? null, $entity->getDiplomeCount());
            if (!$diplomeValidation['ok']) {
                $errors['diplomes'] = $diplomeValidation['message'];
            }
        }

        if ($entity->getExperience() === '') {
            $errors['experience'] = "L'experience est obligatoire.";
        } elseif (mb_strlen($entity->getExperience(), 'UTF-8') < 3) {
            $errors['experience'] = "L'experience doit contenir au moins 3 caracteres.";
        }

        $cvValidation = $this->validatePdfFile($files['cv'] ?? null, 'Le CV');
        if (!$cvValidation['ok']) {
            $errors['cv'] = $cvValidation['message'];
        }

        return $errors;
    }

    public function prepareEntrepriseRegistrationData(EntrepriseEntity $entity, array $files): array
    {
        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            throw new RuntimeException($phoneValidation['error']);
        }

        if ($this->emailExists($entity->getEmail())) {
            throw new RuntimeException('Cet email est deja utilise.');
        }

        $upload = $this->cvUploadService->uploadPdf($files['cv'] ?? []);

        return [
            'nom_entreprise' => $entity->getNom(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'telephone' => $phoneValidation['number'],
            'adresse' => $entity->getAdresse(),
            'ville' => $entity->getVille(),
            'secteur' => $entity->getSecteur(),
            'description' => $entity->getDescription(),
            'site_web' => $entity->getSite(),
            'code_fiscal' => $entity->getCodeFiscal(),
            'cv' => $upload['publicPath'],
        ];
    }

    public function prepareUtilisateurRegistrationData(UserEntity $entity, array $files): array
    {
        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            throw new RuntimeException($phoneValidation['error']);
        }

        if ($this->emailExists($entity->getEmail())) {
            throw new RuntimeException('Cet email est deja utilise.');
        }

        $upload = $this->cvUploadService->uploadPdf($files['cv'] ?? []);

        return [
            'nom' => $entity->getNom(),
            'prenom' => $entity->getPrenom(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'telephone' => $phoneValidation['number'],
            'niveau_etude' => $entity->getNiveau(),
            'domaine' => $entity->getDomaine(),
            'competences' => $entity->getCompetences(),
            'cv' => $upload['publicPath'],
        ];
    }

    public function prepareFormateurRegistrationData(FormateurEntity $entity, array $files): array
    {
        $phoneValidation = $this->normalisePhone($entity->getTelephone());
        if (!$phoneValidation['isValid']) {
            throw new RuntimeException($phoneValidation['error']);
        }

        if ($this->emailExists($entity->getEmail())) {
            throw new RuntimeException('Cet email est deja utilise.');
        }

        $upload = $this->cvUploadService->uploadPdf($files['cv'] ?? []);

        return [
            'nom' => $entity->getNom(),
            'prenom' => $entity->getPrenom(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'telephone' => $phoneValidation['number'],
            'specialite' => $entity->getSpecialite(),
            'diplomes' => (string) $entity->getDiplomeCount(),
            'experience' => $entity->getExperience(),
            'cv' => $upload['publicPath'],
        ];
    }

    public function hasErrors(array $errors): bool
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return true;
            }
        }

        return false;
    }

    public function firstError(array $errors): string
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return (string) $error;
            }
        }

        return 'Le formulaire contient des erreurs.';
    }

    private function validateEmail(string $email): string
    {
        if ($email === '') {
            return "L'email est obligatoire.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Veuillez saisir une adresse email valide.';
        }

        if ($this->emailExists($email)) {
            return 'Cet email est deja utilise.';
        }

        return '';
    }

    private function validatePassword(string $password): string
    {
        if ($password === '') {
            return 'Le mot de passe est obligatoire.';
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password)) {
            return 'Le mot de passe doit contenir au moins 8 caracteres avec 1 majuscule, 1 minuscule, 1 chiffre et 1 caractere special.';
        }

        return '';
    }

    private function validatePdfFile(mixed $file, string $label): array
    {
        if (!is_array($file)) {
            return ['ok' => false, 'message' => $label . ' est obligatoire.'];
        }

        $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return ['ok' => false, 'message' => $label . ' est obligatoire.'];
        }

        if ($uploadError !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'message' => $label . ' est invalide.'];
        }

        $name = mb_strtolower((string) ($file['name'] ?? ''), 'UTF-8');
        if (pathinfo($name, PATHINFO_EXTENSION) !== 'pdf') {
            return ['ok' => false, 'message' => $label . ' doit etre un fichier PDF.'];
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size > 2 * 1024 * 1024) {
            return ['ok' => false, 'message' => $label . ' ne doit pas depasser 2 Mo.'];
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_file($tmpName)) {
            return ['ok' => false, 'message' => $label . ' est invalide.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        if ($mime !== 'application/pdf') {
            return ['ok' => false, 'message' => $label . ' doit avoir le type MIME application/pdf.'];
        }

        return ['ok' => true, 'message' => ''];
    }

    private function validateDiplomaFiles(mixed $files, int $expectedCount): array
    {
        if ($expectedCount <= 0) {
            return ['ok' => false, 'message' => 'Le nombre de diplomes doit etre un entier positif.'];
        }

        if (!is_array($files) || !isset($files['name']) || !is_array($files['name'])) {
            return ['ok' => false, 'message' => 'Veuillez joindre tous les diplomes en PDF.'];
        }

        $names = $files['name'];
        $errors = $files['error'] ?? [];
        $tmpNames = $files['tmp_name'] ?? [];
        $sizes = $files['size'] ?? [];

        $uploadedCount = 0;
        $totalSlots = max(count($names), count($errors), count($tmpNames), count($sizes));

        for ($i = 0; $i < $totalSlots; $i++) {
            $name = (string) ($names[$i] ?? '');
            $error = (int) ($errors[$i] ?? UPLOAD_ERR_NO_FILE);
            $tmpName = (string) ($tmpNames[$i] ?? '');
            $size = (int) ($sizes[$i] ?? 0);

            if ($error === UPLOAD_ERR_NO_FILE && $name === '') {
                continue;
            }

            $uploadedCount++;

            $file = [
                'name' => $name,
                'error' => $error,
                'tmp_name' => $tmpName,
                'size' => $size,
            ];

            $validation = $this->validatePdfFile($file, 'Le diplome');
            if (!$validation['ok']) {
                return $validation;
            }
        }

        if ($uploadedCount !== $expectedCount) {
            return ['ok' => false, 'message' => 'Veuillez joindre tous les diplomes en PDF.'];
        }

        return ['ok' => true, 'message' => ''];
    }
}
