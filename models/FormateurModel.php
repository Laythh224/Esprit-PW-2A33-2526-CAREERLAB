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
        $stmt = $this->conn->query('SELECT id, nom, prenom, email, telephone, specialite, diplomes, experience FROM formateur ORDER BY id DESC');

        return $stmt->fetchAll() ?: [];
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) AS total FROM formateur');

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
        $nom = trim((string) ($post['nom'] ?? ''));
        $prenom = trim((string) ($post['prenom'] ?? ''));
        $email = trim((string) ($post['email'] ?? ''));
        $telephone = trim((string) ($post['telephone'] ?? ''));
        $specialite = trim((string) ($post['specialite'] ?? ''));
        $diplomes = trim((string) ($post['diplomes'] ?? ''));
        $experience = trim((string) ($post['experience'] ?? ''));
        $password = (string) ($post['password'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            throw new RuntimeException('Veuillez remplir tous les champs obligatoires.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Adresse email invalide.');
        }

        if ($this->emailExistsInAnyAccount($email)) {
            throw new RuntimeException('Cet email est deja utilise sur le site.');
        }

        $storedCvPath = null;

        try {
            $upload = $this->cvUploadService->uploadPdf($files['cv'] ?? []);
            $storedCvPath = $upload['diskPath'];

            $this->create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => $password,
                'telephone' => $telephone,
                'specialite' => $specialite,
                'diplomes' => $diplomes,
                'experience' => $experience,
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
}
