<?php

namespace App\models;

use PDO;

class PlanningOptionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query(
            'SELECT po.id_planning, p.nom_de_formation, po.id_option, o.nom_option
             FROM planning_option po
             INNER JOIN planning p ON p.id_planning = po.id_planning
             INNER JOIN optionn o ON o.id_option = po.id_option
             ORDER BY po.id_planning ASC, po.id_option ASC'
        );

        return $statement->fetchAll();
    }

    public function planningChoices(): array
    {
        $statement = $this->db->query('SELECT id_planning, nom_de_formation FROM planning ORDER BY id_planning ASC');
        return $statement->fetchAll();
    }

    public function optionChoices(): array
    {
        $statement = $this->db->query('SELECT id_option, nom_option FROM optionn ORDER BY id_option ASC');
        return $statement->fetchAll();
    }

    public function exists(int $idPlanning, int $idOption): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM planning_option WHERE id_planning = :id_planning AND id_option = :id_option');
        $statement->execute([
            ':id_planning' => $idPlanning,
            ':id_option' => $idOption,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(int $idPlanning, int $idOption): void
    {
        $statement = $this->db->prepare('INSERT INTO planning_option (id_planning, id_option) VALUES (:id_planning, :id_option)');
        $statement->execute([
            ':id_planning' => $idPlanning,
            ':id_option' => $idOption,
        ]);
    }

    public function delete(int $idPlanning, int $idOption): void
    {
        $statement = $this->db->prepare('DELETE FROM planning_option WHERE id_planning = :id_planning AND id_option = :id_option');
        $statement->execute([
            ':id_planning' => $idPlanning,
            ':id_option' => $idOption,
        ]);
    }
}
