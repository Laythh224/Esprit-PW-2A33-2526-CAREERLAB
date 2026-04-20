<?php

namespace App\models;

use PDO;

class OptionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query('SELECT id_option, nom_option, specialite, description FROM optionn ORDER BY id_option ASC');
        return $statement->fetchAll();
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare('INSERT INTO optionn (nom_option, specialite, description) VALUES (:nom_option, :specialite, :description)');
        $statement->execute([
            ':nom_option' => $data['nom_option'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->db->prepare('UPDATE optionn SET nom_option = :nom_option, specialite = :specialite, description = :description WHERE id_option = :id_option');
        $statement->execute([
            ':id_option' => $id,
            ':nom_option' => $data['nom_option'],
            ':specialite' => $data['specialite'],
            ':description' => $data['description'],
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->beginTransaction();

        try {
            $deleteLinks = $this->db->prepare('DELETE FROM planning_option WHERE id_option = :id_option');
            $deleteLinks->execute([':id_option' => $id]);

            $deleteOption = $this->db->prepare('DELETE FROM optionn WHERE id_option = :id_option');
            $deleteOption->execute([':id_option' => $id]);

            $this->db->commit();
        } catch (\Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }
}
