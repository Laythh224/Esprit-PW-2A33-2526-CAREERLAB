<?php

class UserRepository extends AbstractPdoRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        $result = $this->queryOrFail('SELECT id, nom, prenom, email, telephone, niveau_etude, domaine FROM utilisateur ORDER BY id DESC');
        $items = [];

        while ($row = $result->fetch()) {
            $items[] = User::fromArray($row)->toArray();
        }

        return $items;
    }

    public function countAll(): int
    {
        $result = $this->queryOrFail('SELECT COUNT(*) AS total FROM utilisateur');
        $value = $result->fetchColumn();

        return (int) ($value !== false ? $value : 0);
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
        $stmt = $this->prepareOrFail('INSERT INTO utilisateur (nom, prenom, email, password, telephone, niveau_etude, domaine, competences, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $this->executeStatementOrFail($stmt, [$nom, $prenom, $email, $passwordHash, $telephone, $niveau, $domaine, $competences, $cv]);
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
            $stmt = $this->prepareOrFail('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, niveau_etude = ?, domaine = ?, password = ? WHERE id = ?');
            $params = [$nom, $prenom, $email, $telephone, $niveau, $domaine, $passwordHash, $id];
        } else {
            $stmt = $this->prepareOrFail('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, niveau_etude = ?, domaine = ? WHERE id = ?');
            $params = [$nom, $prenom, $email, $telephone, $niveau, $domaine, $id];
        }

        $this->executeStatementOrFail($stmt, $params);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->prepareOrFail('DELETE FROM utilisateur WHERE id = ?');
        $this->executeStatementOrFail($stmt, [$id]);
    }
}
