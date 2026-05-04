<?php

class FormateurModel
{
    private PDO $conn;
    private CvService $cvUploadService;
    private SignupValidator $signupValidator;

    public function __construct(PDO $conn, ?CvService $cvUploadService = null, ?SignupValidator $signupValidator = null)
    {
        $this->conn = $conn;
        $this->cvUploadService = $cvUploadService ?? new CvService();
        $this->signupValidator = $signupValidator ?? new SignupValidator($conn, null, $this->cvUploadService);
    }

    public function all(?string $verificationFilter = null): array
    {
        $sql = 'SELECT id, nom, prenom, email, telephone, specialite, diplomes, experience, verified, verified_at, verified_by FROM formateur';

        if ($verificationFilter === 'verified') {
            $sql .= ' WHERE verified = 1';
        } elseif ($verificationFilter === 'unverified') {
            $sql .= ' WHERE verified = 0';
        }

        $sql .= ' ORDER BY id DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll() ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM formateur WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $account = $stmt->fetch();

        return $account !== false ? $account : null;
    }

    public function updateVerificationStatus(int $id, bool $verified, ?int $adminId = null): bool
    {
        if ($id <= 0) {
            return false;
        }

        if ($verified) {
            $stmt = $this->conn->prepare('UPDATE formateur SET verified = 1, verified_at = NOW(), verified_by = ? WHERE id = ?');
            return $stmt->execute([$adminId, $id]);
        }

        $stmt = $this->conn->prepare('UPDATE formateur SET verified = 0, verified_at = NULL, verified_by = NULL WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function logVerificationChange(int $accountId, ?int $adminId, bool $previousVerified, bool $newVerified): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO account_verification_logs (account_type, account_id, admin_id, previous_verified, new_verified, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute(['formateur', $accountId, $adminId, $previousVerified ? 1 : 0, $newVerified ? 1 : 0]);
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

        if ($this->signupValidator->hasErrors($errors)) {
            throw new RuntimeException($this->signupValidator->firstError($errors));
        }

        $data = $this->prepareRegistrationData($entity, $files);
        $this->create($data);
    }

    public function validateSignup(FormateurEntity $entity, array $files): array
    {
        return $this->signupValidator->validateFormateur($entity, $files);
    }

    public function prepareRegistrationData(FormateurEntity $entity, array $files): array
    {
        return $this->signupValidator->prepareFormateurRegistrationData($entity, $files);
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
            throw new InvalidArgumentException('Nom, prenom, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                'INSERT INTO utilisateur (nom, prenom, email, password, telephone, niveau_etude, domaine, competences, cv, email_verified)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)'
            );
            $stmt->execute([$nom, $prenom, $email, $passwordHash, $telephone, '', '', '', $cv]);

            $userId = (int) $this->conn->lastInsertId();
            if ($userId <= 0) {
                throw new RuntimeException('Creation utilisateur invalide.');
            }

            $stmt = $this->conn->prepare(
                'INSERT INTO formateur (id, nom, prenom, email, password, telephone, specialite, diplomes, experience, cv, email_verified)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)'
            );
            $stmt->execute([$userId, $nom, $prenom, $email, $passwordHash, $telephone, $specialite, $diplomes, $experience, $cv]);

            $this->conn->commit();
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $exception;
        }
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
            throw new InvalidArgumentException('Donnees de mise a jour invalides.');
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

        $stmt = $this->conn->prepare('DELETE FROM inscription_formateur WHERE id_formateur = ?');
        $stmt->execute([$id]);

        $stmt = $this->conn->prepare('DELETE FROM formateur WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function hydrateSignupEntity(array $post): FormateurEntity
    {
        $entity = new FormateurEntity();
        $diplomeCount = filter_var((string) ($post['diplome_count'] ?? ''), FILTER_VALIDATE_INT);

        $entity
            ->setNom($this->signupValidator->cleanText((string) ($post['nom'] ?? '')))
            ->setPrenom($this->signupValidator->cleanText((string) ($post['prenom'] ?? '')))
            ->setEmail($this->signupValidator->cleanEmail((string) ($post['email'] ?? '')))
            ->setTelephone($this->signupValidator->cleanText((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setSpecialite($this->signupValidator->cleanText((string) ($post['specialite'] ?? '')))
            ->setDiplomes($this->signupValidator->cleanText((string) ($post['diplome_count'] ?? '')))
            ->setDiplomeCount($diplomeCount === false ? 0 : (int) $diplomeCount)
            ->setExperience($this->signupValidator->cleanMultilineText((string) ($post['experience'] ?? '')));

        return $entity;
    }
}
