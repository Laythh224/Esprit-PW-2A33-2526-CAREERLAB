<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class FormationModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query(
            'SELECT nom_formation, specialite, description, niveau, nb_place
             FROM formation
             ORDER BY nom_formation ASC'
        );

        return $statement->fetchAll();
    }

    public function names(): array
    {
        $statement = $this->db->query('SELECT nom_formation FROM formation ORDER BY nom_formation ASC');

        return array_map(
            static fn (array $row): string => (string) $row['nom_formation'],
            $statement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function exists(string $nomFormation): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM formation WHERE nom_formation = :nom_formation');
        $statement->execute([':nom_formation' => $nomFormation]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO formation (nom_formation, specialite, description, niveau, nb_place)
             VALUES (:nom_formation, :specialite, :description, :niveau, :nb_place)'
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
            ':niveau' => $data['niveau'],
            ':nb_place' => $data['nb_place'],
        ]);
    }

    public function update(string $oldNomFormation, array $data): void
    {
        $statement = $this->db->prepare(
            'UPDATE formation
             SET nom_formation = :nom_formation,
                 specialite = :specialite,
                 description = :description,
                 niveau = :niveau,
                 nb_place = :nb_place
             WHERE nom_formation = :old_nom_formation'
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
            ':niveau' => $data['niveau'],
            ':nb_place' => $data['nb_place'],
            ':old_nom_formation' => $oldNomFormation,
        ]);
    }

    public function delete(string $nomFormation): void
    {
        $statement = $this->db->prepare('DELETE FROM formation WHERE nom_formation = :nom_formation');
        $statement->execute([':nom_formation' => $nomFormation]);
    }
}
