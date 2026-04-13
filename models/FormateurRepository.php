<?php

class FormateurRepository extends AbstractPdoRepository implements FormateurRepositoryInterface
{
    public function all(): array
    {
        $result = $this->queryOrFail('SELECT id, nom, prenom, email, telephone, specialite, diplomes, experience FROM formateur ORDER BY id DESC');
        $items = [];

        while ($row = $result->fetch()) {
            $items[] = Formateur::fromArray($row)->toArray();
        }

        return $items;
    }

    public function countAll(): int
    {
        $result = $this->queryOrFail('SELECT COUNT(*) AS total FROM formateur');
        $value = $result->fetchColumn();

        return (int) ($value !== false ? $value : 0);
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
        $stmt = $this->prepareOrFail('INSERT INTO formateur (nom, prenom, email, password, telephone, specialite, diplomes, experience, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $this->executeStatementOrFail($stmt, [$nom, $prenom, $email, $passwordHash, $telephone, $specialite, $diplomes, $experience, $cv]);
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
            $stmt = $this->prepareOrFail('UPDATE formateur SET nom = ?, prenom = ?, email = ?, telephone = ?, specialite = ?, diplomes = ?, experience = ?, password = ? WHERE id = ?');
            $params = [$nom, $prenom, $email, $telephone, $specialite, $diplomes, $experience, $passwordHash, $id];
        } else {
            $stmt = $this->prepareOrFail('UPDATE formateur SET nom = ?, prenom = ?, email = ?, telephone = ?, specialite = ?, diplomes = ?, experience = ? WHERE id = ?');
            $params = [$nom, $prenom, $email, $telephone, $specialite, $diplomes, $experience, $id];
        }

        $this->executeStatementOrFail($stmt, $params);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID invalide.');
        }

        $stmt = $this->prepareOrFail('DELETE FROM formateur WHERE id = ?');
        $this->executeStatementOrFail($stmt, [$id]);
    }
}
