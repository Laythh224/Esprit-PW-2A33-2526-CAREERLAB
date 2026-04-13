<?php

class EntrepriseRepository extends AbstractPdoRepository implements EntrepriseRepositoryInterface
{
    public function all(): array
    {
        $result = $this->queryOrFail('SELECT id, nom_entreprise, email, telephone, adresse, ville, secteur, description, site_web FROM entreprise ORDER BY id DESC');
        $items = [];

        while ($row = $result->fetch()) {
            $items[] = Entreprise::fromArray($row)->toArray();
        }

        return $items;
    }

    public function countAll(): int
    {
        $statsResult = $this->queryOrFail('SELECT COUNT(*) AS total FROM entreprise');
        $value = $statsResult->fetchColumn();

        return (int) ($value !== false ? $value : 0);
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
        $stmt = $this->prepareOrFail('INSERT INTO entreprise (nom_entreprise, email, password, telephone, adresse, ville, secteur, description, site_web) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $this->executeStatementOrFail($stmt, [$nomEntreprise, $email, $passwordHash, $telephone, $adresse, $ville, $secteur, $description, $siteWeb]);
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
            $stmt = $this->prepareOrFail('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ?, password = ? WHERE id = ?');
            $params = [$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $passwordHash, $id];
        } else {
            $stmt = $this->prepareOrFail('UPDATE entreprise SET nom_entreprise = ?, email = ?, telephone = ?, adresse = ?, ville = ?, secteur = ?, description = ?, site_web = ? WHERE id = ?');
            $params = [$nomEntreprise, $email, $telephone, $adresse, $ville, $secteur, $description, $siteWeb, $id];
        }

        $this->executeStatementOrFail($stmt, $params);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->prepareOrFail('DELETE FROM entreprise WHERE id = ?');
        $this->executeStatementOrFail($stmt, [$id]);
    }
}
