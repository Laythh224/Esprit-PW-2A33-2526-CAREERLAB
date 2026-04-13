<?php

class UserModel
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
        $stmt = $this->conn->query('SELECT id, nom, prenom, email, telephone, niveau_etude, domaine FROM utilisateur ORDER BY id DESC');

        return $stmt->fetchAll() ?: [];
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) AS total FROM utilisateur');

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
        $nom = trim((string) ($post['nom'] ?? ''));
        $prenom = trim((string) ($post['prenom'] ?? ''));
        $email = trim((string) ($post['email'] ?? ''));
        $telephone = trim((string) ($post['telephone'] ?? ''));
        $niveauEtude = trim((string) ($post['niveau'] ?? ''));
        $domaine = trim((string) ($post['domaine'] ?? ''));
        $competences = trim((string) ($post['competences'] ?? ''));
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
                'niveau_etude' => $niveauEtude,
                'domaine' => $domaine,
                'competences' => $competences,
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
        $niveau = trim((string) ($data['niveau_etude'] ?? ''));
        $domaine = trim((string) ($data['domaine'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $cv = trim((string) ($data['cv'] ?? ''));
        $competences = trim((string) ($data['competences'] ?? ''));

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom, prénom, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO utilisateur (nom, prenom, email, password, telephone, niveau_etude, domaine, competences, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
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

        $stmt = $this->conn->prepare('DELETE FROM utilisateur WHERE id = ?');
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
