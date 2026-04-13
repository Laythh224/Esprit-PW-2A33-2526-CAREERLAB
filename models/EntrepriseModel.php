<?php

class EntrepriseModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function all(): array
    {
        $stmt = $this->conn->query('SELECT id, nom_entreprise, email, telephone, adresse, ville, secteur, description, site_web FROM entreprise ORDER BY id DESC');

        return $stmt->fetchAll() ?: [];
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) AS total FROM entreprise');

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM entreprise WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        return $account !== false ? $account : null;
    }

    public function registerFromSignup(array $post): void
    {
        $nomEntreprise = trim((string) ($post['nom'] ?? ''));
        $email = trim((string) ($post['email'] ?? ''));
        $telephone = trim((string) ($post['telephone'] ?? ''));
        $adresse = trim((string) ($post['adresse'] ?? ''));
        $ville = trim((string) ($post['ville'] ?? ''));
        $secteur = trim((string) ($post['secteur'] ?? ''));
        $description = trim((string) ($post['description'] ?? ''));
        $siteWeb = trim((string) ($post['site'] ?? ''));
        $password = (string) ($post['password'] ?? '');

        if ($nomEntreprise === '' || $email === '' || $password === '') {
            throw new RuntimeException('Veuillez remplir tous les champs obligatoires.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Adresse email invalide.');
        }

        if ($siteWeb !== '' && !filter_var($siteWeb, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Le site web doit etre une URL valide.');
        }

        if ($this->emailExistsInAnyAccount($email)) {
            throw new RuntimeException('Cet email est deja utilise sur le site.');
        }

        $this->create([
            'nom_entreprise' => $nomEntreprise,
            'email' => $email,
            'password' => $password,
            'telephone' => $telephone,
            'adresse' => $adresse,
            'ville' => $ville,
            'secteur' => $secteur,
            'description' => $description,
            'site_web' => $siteWeb,
        ]);
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
        $password = (string) ($data['password'] ?? '');

        if ($nomEntreprise === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Nom entreprise, email et mot de passe sont obligatoires.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare('INSERT INTO entreprise (nom_entreprise, email, password, telephone, adresse, ville, secteur, description, site_web) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nomEntreprise, $email, $passwordHash, $telephone, $adresse, $ville, $secteur, $description, $siteWeb]);
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
        $password = (string) ($data['password'] ?? '');

        if ($id <= 0 || $nomEntreprise === '' || $email === '') {
            throw new InvalidArgumentException('Données de mise à jour invalides.');
        }

        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ?, password = ? WHERE id = ?');
            $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $passwordHash, $id]);
            return;
        }

        $stmt = $this->conn->prepare('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ? WHERE id = ?');
        $stmt->execute([$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $id]);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->conn->prepare('DELETE FROM entreprise WHERE id = ?');
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
