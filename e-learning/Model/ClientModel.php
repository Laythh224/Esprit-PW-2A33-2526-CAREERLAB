<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class ClientModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query(
            'SELECT cin, nom, prenom, adresse, niveau, age, tel, nom_formation, session_id
             FROM client
             ORDER BY nom ASC, prenom ASC'
        );

        return $statement->fetchAll();
    }

    public function find(string $cin): ?array
    {
        $statement = $this->db->prepare(
            'SELECT cin, nom, prenom, adresse, niveau, age, tel, nom_formation, session_id FROM client WHERE cin = :cin'
        );
        $statement->execute([':cin' => $cin]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    public function exists(string $cin): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM client WHERE cin = :cin');
        $statement->execute([':cin' => $cin]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO client (cin, nom, prenom, adresse, niveau, age, tel, nom_formation, session_id)
             VALUES (:cin, :nom, :prenom, :adresse, :niveau, :age, :tel, :nom_formation, :session_id)'
        );
        $statement->execute([
            ':cin' => $data['cin'],
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':adresse' => $data['adresse'],
            ':niveau' => $data['niveau'],
            ':age' => $data['age'],
            ':tel' => $data['tel'],
            ':nom_formation' => $data['nom_formation'] ?? null,
            ':session_id' => isset($data['session_id']) ? (int) $data['session_id'] : null,
        ]);
    }

    public function update(string $oldCin, array $data): void
    {
        $statement = $this->db->prepare(
            'UPDATE client
             SET cin = :cin,
                 nom = :nom,
                 prenom = :prenom,
                 adresse = :adresse,
                 niveau = :niveau,
                 age = :age,
                 tel = :tel,
                 nom_formation = :nom_formation,
                 session_id = :session_id
             WHERE cin = :old_cin'
        );
        $statement->execute([
            ':cin' => $data['cin'],
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':adresse' => $data['adresse'],
            ':niveau' => $data['niveau'],
            ':age' => $data['age'],
            ':tel' => $data['tel'],
            ':nom_formation' => $data['nom_formation'] ?? null,
            ':session_id' => isset($data['session_id']) ? (int) $data['session_id'] : null,
            ':old_cin' => $oldCin,
        ]);
    }

    public function delete(string $cin): void
    {
        $statement = $this->db->prepare('DELETE FROM client WHERE cin = :cin');
        $statement->execute([':cin' => $cin]);
    }
}
