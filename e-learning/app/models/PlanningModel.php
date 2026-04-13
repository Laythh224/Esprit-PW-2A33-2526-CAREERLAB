<?php

namespace App\models;

use PDO;

class PlanningModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query('SELECT id_planning, nom_de_formation, date_debut, date_fin, TYPE FROM planning ORDER BY id_planning ASC');
        return $statement->fetchAll();
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare('INSERT INTO planning (nom_de_formation, date_debut, date_fin, TYPE) VALUES (:nom_de_formation, :date_debut, :date_fin, :TYPE)');
        $statement->execute([
            ':nom_de_formation' => $data['nom_de_formation'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':TYPE' => $data['TYPE'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->db->prepare('UPDATE planning SET nom_de_formation = :nom_de_formation, date_debut = :date_debut, date_fin = :date_fin, TYPE = :TYPE WHERE id_planning = :id_planning');
        $statement->execute([
            ':id_planning' => $id,
            ':nom_de_formation' => $data['nom_de_formation'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':TYPE' => $data['TYPE'],
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->beginTransaction();

        try {
            $deleteLinks = $this->db->prepare('DELETE FROM planning_option WHERE id_planning = :id_planning');
            $deleteLinks->execute([':id_planning' => $id]);

            $deletePlanning = $this->db->prepare('DELETE FROM planning WHERE id_planning = :id_planning');
            $deletePlanning->execute([':id_planning' => $id]);

            $this->db->commit();
        } catch (\Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }
}
