<?php

class EntrepriseModel
{
    private PDO $conn;
    private SignupValidator $signupValidator;

    public function __construct(PDO $conn, ?SignupValidator $signupValidator = null)
    {
        $this->conn = $conn;
        $this->signupValidator = $signupValidator ?? new SignupValidator($conn);
    }

    public function all(?string $verificationFilter = null): array
    {
        $sql = 'SELECT id, nom_entreprise, email, telephone, adresse, ville, secteur, description, site_web, verified, verified_at, verified_by FROM entreprise';

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
        $stmt = $this->conn->prepare('SELECT * FROM entreprise WHERE id = ? LIMIT 1');
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
            $stmt = $this->conn->prepare('UPDATE entreprise SET verified = 1, verified_at = NOW(), verified_by = ? WHERE id = ?');
            return $stmt->execute([$adminId, $id]);
        }

        $stmt = $this->conn->prepare('UPDATE entreprise SET verified = 0, verified_at = NULL, verified_by = NULL WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function logVerificationChange(int $accountId, ?int $adminId, bool $previousVerified, bool $newVerified): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO account_verification_logs (account_type, account_id, admin_id, previous_verified, new_verified, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute(['entreprise', $accountId, $adminId, $previousVerified ? 1 : 0, $newVerified ? 1 : 0]);
    }

    public function count(): int
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM entreprise');
        $stmt->execute();

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM entreprise WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        return $account !== false ? $account : null;
    }

    public function registerFromSignup(array $post, array $files = []): void
    {
        $entity = $this->hydrateSignupEntity($post);
        $errors = $this->validateSignup($entity, $files);

        if ($this->signupValidator->hasErrors($errors)) {
            throw new RuntimeException($this->signupValidator->firstError($errors));
        }

        $data = $this->prepareRegistrationData($entity, $files);
        $this->create($data);
    }

    public function validateSignup(EntrepriseEntity $entity, array $files = []): array
    {
        return $this->signupValidator->validateEntreprise($entity, $files);
    }

    public function prepareRegistrationData(EntrepriseEntity $entity, array $files = []): array
    {
        return $this->signupValidator->prepareEntrepriseRegistrationData($entity, $files);
    }

    public function create(array $data): void
    {
        $nomEntreprise = trim((string) ($data['nom_entreprise'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $adresse = trim((string) ($data['adresse'] ?? ''));
        $ville = trim((string) ($data['ville'] ?? ''));
        $secteur = trim((string) ($data['secteur'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $siteWeb = trim((string) ($data['site_web'] ?? ''));
        $codeFiscal = trim((string) ($data['code_fiscal'] ?? ''));
        $cv = trim((string) ($data['cv'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($nomEntreprise === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom entreprise, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO entreprise (nom_entreprise, email, password, telephone, adresse, ville, secteur, description, site_web, code_fiscal, cv, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
        $stmt->execute([$nomEntreprise, $email, $passwordHash, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $codeFiscal, $cv]);
    }

    public function update(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);
        $nomEntreprise = trim((string) ($data['nom_entreprise'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $adresse = trim((string) ($data['adresse'] ?? ''));
        $ville = trim((string) ($data['ville'] ?? ''));
        $secteur = trim((string) ($data['secteur'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $siteWeb = trim((string) ($data['site_web'] ?? ''));
        $codeFiscal = trim((string) ($data['code_fiscal'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($id <= 0 || $nomEntreprise === '' || $email === '') {
            throw new InvalidArgumentException('Donnees de mise a jour invalides.');
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ?, code_fiscal = ?, password = ? WHERE id = ?');
            $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $codeFiscal, $passwordHash, $id]);
            return;
        }

        $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ?, code_fiscal = ? WHERE id = ?');
        $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $codeFiscal, $id]);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->conn->prepare('DELETE FROM inscription_entreprise WHERE id_entreprise = ?');
        $stmt->execute([$id]);

        $stmt = $this->conn->prepare('DELETE FROM entreprise WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function hydrateSignupEntity(array $post): EntrepriseEntity
    {
        $entity = new EntrepriseEntity();

        $entity
            ->setNom($this->signupValidator->cleanText((string) ($post['nom'] ?? '')))
            ->setEmail($this->signupValidator->cleanEmail((string) ($post['email'] ?? '')))
            ->setTelephone($this->signupValidator->cleanText((string) ($post['telephone'] ?? '')))
            ->setAdresse($this->signupValidator->cleanText((string) ($post['adresse'] ?? '')))
            ->setVille($this->signupValidator->cleanText((string) ($post['ville'] ?? '')))
            ->setSecteur($this->signupValidator->cleanText((string) ($post['secteur'] ?? '')))
            ->setDescription($this->signupValidator->cleanMultilineText((string) ($post['description'] ?? '')))
            ->setSite($this->signupValidator->cleanText((string) ($post['site'] ?? '')))
            ->setCodeFiscal($this->signupValidator->cleanText((string) ($post['code_fiscal'] ?? '')))
            ->setPassword((string) ($post['password'] ?? ''))
            ->setConfirmPassword((string) ($post['confirm_password'] ?? ''));

        return $entity;
    }
}
