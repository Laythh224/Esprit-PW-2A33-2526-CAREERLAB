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
            'SELECT nom_formation, specialite, description, date_debut, date_fin, niveau, duree
             FROM formation
             ORDER BY date_debut DESC, nom_formation ASC'
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
            'INSERT INTO formation (nom_formation, specialite, description, date_debut, date_fin, niveau, duree)
             VALUES (:nom_formation, :specialite, :description, :date_debut, :date_fin, :niveau, :duree)'
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':niveau' => $data['niveau'],
            ':duree' => $data['duree'],
        ]);
    }

    public function update(string $oldNomFormation, array $data): void
    {
        $statement = $this->db->prepare(
            'UPDATE formation
             SET nom_formation = :nom_formation,
                 specialite = :specialite,
                 description = :description,
                 date_debut = :date_debut,
                 date_fin = :date_fin,
                 niveau = :niveau,
                 duree = :duree
             WHERE nom_formation = :old_nom_formation'
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':niveau' => $data['niveau'],
            ':duree' => $data['duree'],
            ':old_nom_formation' => $oldNomFormation,
        ]);
    }

    public function delete(string $nomFormation): void
    {
        $statement = $this->db->prepare('DELETE FROM formation WHERE nom_formation = :nom_formation');
        $statement->execute([':nom_formation' => $nomFormation]);
    }
}

