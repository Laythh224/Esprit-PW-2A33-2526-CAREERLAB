<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class CritereModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT id, nom_formation, type, lien, duree_online, adresse, salle, duree_presentiel
             FROM critere
             ORDER BY id DESC"
        );

        return $statement->fetchAll();
    }

    public function byFormation(): array
    {
        $statement = $this->db->query(
            "SELECT f.nom_formation, f.specialite, f.description, f.date_debut, f.date_fin, f.niveau, f.duree,
                    c.id AS critere_id, c.type, c.lien, c.duree_online, c.adresse, c.salle, c.duree_presentiel
             FROM formation f
             LEFT JOIN critere c ON c.nom_formation = f.nom_formation
             ORDER BY f.date_debut DESC, f.nom_formation ASC, c.id ASC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare(
            "INSERT INTO critere (nom_formation, type, lien, duree_online, adresse, salle, duree_presentiel)
             VALUES (:nom_formation, :type, :lien, :duree_online, :adresse, :salle, :duree_presentiel)"
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':type' => $data['type'],
            ':lien' => $data['lien'],
            ':duree_online' => $data['duree_online'],
            ':adresse' => $data['adresse'],
            ':salle' => $data['salle'],
            ':duree_presentiel' => $data['duree_presentiel'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->db->prepare(
            "UPDATE critere
             SET nom_formation = :nom_formation,
                 type = :type,
                 lien = :lien,
                 duree_online = :duree_online,
                 adresse = :adresse,
                 salle = :salle,
                 duree_presentiel = :duree_presentiel
             WHERE id = :id"
        );
        $statement->execute([
            ':id' => $id,
            ':nom_formation' => $data['nom_formation'],
            ':type' => $data['type'],
            ':lien' => $data['lien'],
            ':duree_online' => $data['duree_online'],
            ':adresse' => $data['adresse'],
            ':salle' => $data['salle'],
            ':duree_presentiel' => $data['duree_presentiel'],
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM critere WHERE id = :id');
        $statement->execute([':id' => $id]);
    }
}

