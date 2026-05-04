<?php

class UserModel
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
        $sql = 'SELECT id, nom, prenom, email, telephone, niveau_etude, domaine, role, verified, verified_at, verified_by FROM utilisateur';
        $params = [];

        if ($verificationFilter === 'verified') {
            $sql .= ' WHERE verified = 1';
        } elseif ($verificationFilter === 'unverified') {
            $sql .= ' WHERE verified = 0';
        }

        $sql .= ' ORDER BY id DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll() ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM utilisateur WHERE id = ? LIMIT 1');
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
            $stmt = $this->conn->prepare('UPDATE utilisateur SET verified = 1, verified_at = NOW(), verified_by = ? WHERE id = ?');
            return $stmt->execute([$adminId, $id]);
        }

        $stmt = $this->conn->prepare('UPDATE utilisateur SET verified = 0, verified_at = NULL, verified_by = NULL WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function logVerificationChange(int $accountId, ?int $adminId, bool $previousVerified, bool $newVerified): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO account_verification_logs (account_type, account_id, admin_id, previous_verified, new_verified, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute(['utilisateur', $accountId, $adminId, $previousVerified ? 1 : 0, $newVerified ? 1 : 0]);
    }

    public function count(): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM utilisateur');
        $stmt->execute();

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM utilisateur WHERE email = ? LIMIT 1');
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

    public function validateSignup(UserEntity $entity, array $files): array
    {
        return $this->signupValidator->validateUtilisateur($entity, $files);
    }

    public function prepareRegistrationData(UserEntity $entity, array $files): array
    {
        return $this->signupValidator->prepareUtilisateurRegistrationData($entity, $files);
    }

    public function create(array $data): void
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $niveau = trim((string) ($data['niveau_etude'] ?? ''));
        $domaine = trim((string) ($data['domaine'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $cv = trim((string) ($data['cv'] ?? ''));
        $competences = trim((string) ($data['competences'] ?? ''));

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom, prénom, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO utilisateur (nom, prenom, email, password, telephone, niveau_etude, domaine, competences, cv, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
        $stmt->execute([$nom, $prenom, $email, $passwordHash, $telephone, $niveau, $domaine, $competences, $cv]);
    }

    public function update(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $niveau = trim((string) ($data['niveau_etude'] ?? ''));
        $domaine = trim((string) ($data['domaine'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($id <= 0 || $nom === '' || $prenom === '' || $email === '') {
            throw new InvalidArgumentException('Données de mise à jour invalides.');
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, niveau_etude = ?, domaine = ?, password = ? WHERE id = ?');
            $stmt->execute([$nom, $prenom, $email, $telephone, $niveau, $domaine, $passwordHash, $id]);
            return;
        }

        $stmt = $this->conn->prepare('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, niveau_etude = ?, domaine = ? WHERE id = ?');
        $stmt->execute([$nom, $prenom, $email, $telephone, $niveau, $domaine, $id]);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->conn->prepare('DELETE FROM inscription_formateur WHERE id_user = ?');
        $stmt->execute([$id]);

        $stmt = $this->conn->prepare('DELETE FROM inscription_entreprise WHERE id_user = ?');
        $stmt->execute([$id]);

        $stmt = $this->conn->prepare('DELETE FROM utilisateur WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function hydrateSignupEntity(array $post): UserEntity
    {
        $entity = new UserEntity();

        $entity
            ->setNom($this->signupValidator->cleanText((string) ($post['nom'] ?? '')))
            ->setPrenom($this->signupValidator->cleanText((string) ($post['prenom'] ?? '')))
            ->setEmail($this->signupValidator->cleanEmail((string) ($post['email'] ?? '')))
            ->setTelephone($this->signupValidator->cleanText((string) ($post['telephone'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''))
            ->setNiveau($this->signupValidator->cleanText((string) ($post['niveau'] ?? '')))
            ->setDomaine($this->signupValidator->cleanText((string) ($post['domaine'] ?? '')))
            ->setCompetences($this->signupValidator->cleanMultilineText((string) ($post['competences'] ?? '')));

        return $entity;
    }
}
