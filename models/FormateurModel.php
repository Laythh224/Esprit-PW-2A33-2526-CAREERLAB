<?php

class FormateurModel
{
    private PDO $conn;
    private CvUploadService $cvUploadService;

    public function __construct(PDO $conn, ?CvUploadService $cvUploadService = null)
    {
        $this->conn = $conn;
        $this->cvUploadService = $cvUploadService ?? new CvUploadService();
    }

    public function all(): array
    {
        $stmt = $this->conn->prepare('SELECT id, nom, prenom, email, telephone, specialite, diplomes, experience FROM formateur ORDER BY id DESC');
        $stmt->execute();

        return $stmt->fetchAll() ?: [];
    }

    public function count(): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM formateur');
        $stmt->execute();

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM formateur WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        return $account !== false ? $account : null;
    }

    public function registerFromSignup(array $post, array $files): void
    {
        $entity = $this->hydrateSignupEntity($post);
        $errors = $this->validateSignup($entity, $files);

        if ($this->hasValidationErrors($errors)) {
            throw new RuntimeException($this->firstValidationError($errors));
        }

        $this->registerFromEntity($entity, $files);
    }

    public function validateSignup(FormateurEntity $entity, array $files): array
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
        }
        if ($entity->getPrenom() === '') {
            $errors['prenom'] = 'Le prenom est obligatoire.';
        }
        if ($entity->getEmail() === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($entity->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        } elseif ($this->emailExistsInAnyAccount($entity->getEmail())) {
            $errors['email'] = 'Cet email est deja utilise.';
        }
        if ($entity->getTelephone() === '') {
            $errors['telephone'] = 'Le telephone est obligatoire.';
        } elseif (!preg_match('/^\d{8}$/', $entity->getTelephone())) {
            $errors['telephone'] = 'Le telephone doit contenir 8 chiffres.';
        }
        if ($entity->getPassword() === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($entity->getPassword()) < 6) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 6 caracteres.';
        }
        if ($entity->getConfirmPassword() === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($entity->getPassword() !== '' && $entity->getConfirmPassword() !== $entity->getPassword()) {
            $errors['confirm_password'] = 'La confirmation ne correspond pas au mot de passe.';
        }
        if ($entity->getSpecialite() === '') {
            $errors['specialite'] = 'La specialite est obligatoire.';
        }
        if ($entity->getDiplomes() === '') {
            $errors['diplomes'] = 'Les diplomes sont obligatoires.';
        }
        if ($entity->getExperience() === '') {
            $errors['experience'] = "L'experience est obligatoire.";
        }

        $cvFile = $files['cv'] ?? null;
        $uploadError = is_array($cvFile) ? (int) ($cvFile['error'] ?? UPLOAD_ERR_NO_FILE) : UPLOAD_ERR_NO_FILE;
        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            $errors['cv'] = 'Le CV est obligatoire.';
            return $errors;
        }

        if ($uploadError !== UPLOAD_ERR_OK) {
            $errors['cv'] = 'Le fichier CV est invalide.';
            return $errors;
        }

        $cvName = mb_strtolower((string) ($cvFile['name'] ?? ''));
        if (!str_ends_with($cvName, '.pdf')) {
            $errors['cv'] = 'Le CV doit etre au format PDF.';
        }

        return $errors;
    }

    public function registerFromEntity(FormateurEntity $entity, array $files): void
    {
        if ($this->emailExistsInAnyAccount($entity->getEmail())) {
            throw new RuntimeException('Cet email est deja utilise sur le site.');
        }

        $storedCvPath = null;

        try {
            $upload = $this->cvUploadService->uploadPdf($files['cv'] ?? []);
            $storedCvPath = $upload['diskPath'];

            $this->create([
                'nom' => $entity->getNom(),
                'prenom' => $entity->getPrenom(),
                'email' => $entity->getEmail(),
                'password' => $entity->getPassword(),
                'telephone' => $entity->getTelephone(),
                'specialite' => $entity->getSpecialite(),
                'diplomes' => $entity->getDiplomes(),
                'experience' => $entity->getExperience(),
                'cv' => $upload['publicPath'],
            ]);
        } catch (Throwable $exception) {
            $this->cvUploadService->deleteFile($storedCvPath);
            throw $exception;
        }
    }

    public function create(array $data): void
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $specialite = trim((string) ($data['specialite'] ?? ''));
        $diplomes = trim((string) ($data['diplomes'] ?? ''));
        $experience = trim((string) ($data['experience'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $cv = trim((string) ($data['cv'] ?? ''));

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom, prénom, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO formateur (nom, prenom, email, password, telephone, specialite, diplomes, experience, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nom, $prenom, $email, $passwordHash, $telephone, $specialite, $diplomes, $experience, $cv]);
    }

    public function update(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $specialite = trim((string) ($data['specialite'] ?? ''));
        $diplomes = trim((string) ($data['diplomes'] ?? ''));
        $experience = trim((string) ($data['experience'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($id <= 0 || $nom === '' || $prenom === '' || $email === '') {
            throw new InvalidArgumentException('Données de mise à jour invalides.');
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE formateur SET nom = ?, prenom = ?, email = ?, telephone = ?, specialite = ?, diplomes = ?, experience = ?, password = ? WHERE id = ?');
            $stmt->execute([$nom, $prenom, $email, $telephone, $specialite, $diplomes, $experience, $passwordHash, $id]);
            return;
        }

        $stmt = $this->conn->prepare('UPDATE formateur SET nom = ?, prenom = ?, email = ?, telephone = ?, specialite = ?, diplomes = ?, experience = ? WHERE id = ?');
        $stmt->execute([$nom, $prenom, $email, $telephone, $specialite, $diplomes, $experience, $id]);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->conn->prepare('DELETE FROM formateur WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function emailExistsInAnyAccount(string $email): bool
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

    private function hydrateSignupEntity(array $post): FormateurEntity
    {
        $entity = new FormateurEntity();

        $entity
            ->setNom(trim((string) ($post['nom'] ?? '')))
            ->setPrenom(trim((string) ($post['prenom'] ?? '')))
            ->setEmail(trim((string) ($post['email'] ?? '')))
            ->setTelephone(trim((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setSpecialite(trim((string) ($post['specialite'] ?? '')))
            ->setDiplomes(trim((string) ($post['diplomes'] ?? '')))
            ->setExperience(trim((string) ($post['experience'] ?? '')));

        return $entity;
    }

    private function hasValidationErrors(array $errors): bool
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return true;
            }
        }

        return false;
    }

    private function firstValidationError(array $errors): string
    {
        foreach ($errors as $error) {
            if ((string) $error !== '') {
                return (string) $error;
            }
        }

        return 'Le formulaire contient des erreurs.';
    }
}
