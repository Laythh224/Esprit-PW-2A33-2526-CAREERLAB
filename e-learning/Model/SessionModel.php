<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class SessionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $statement = $this->db->query(
            "SELECT id, nom_formation, type, lien, duree_online, adresse, salle, duree_presentiel, date_debut, date_fin
             FROM `session`
             ORDER BY id DESC"
        );

        return $statement->fetchAll();
    }

    public function byFormation(): array
    {
        $statement = $this->db->query(
            "SELECT f.nom_formation, f.specialite, f.description, f.niveau, f.nb_place,
                    s.id AS session_id, s.type, s.lien, s.duree_online, s.adresse, s.salle, s.duree_presentiel,
                    s.date_debut, s.date_fin
             FROM formation f
             LEFT JOIN `session` s ON s.nom_formation = f.nom_formation
             ORDER BY s.date_debut DESC, f.nom_formation ASC, s.id ASC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $statement = $this->db->prepare(
            "INSERT INTO `session` (nom_formation, type, lien, duree_online, adresse, salle, duree_presentiel, date_debut, date_fin)
             VALUES (:nom_formation, :type, :lien, :duree_online, :adresse, :salle, :duree_presentiel, :date_debut, :date_fin)"
        );
        $statement->execute([
            ':nom_formation' => $data['nom_formation'],
            ':type' => $data['type'],
            ':lien' => $data['lien'],
            ':duree_online' => $data['duree_online'],
            ':adresse' => $data['adresse'],
            ':salle' => $data['salle'],
            ':duree_presentiel' => $data['duree_presentiel'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->db->prepare(
            "UPDATE `session`
             SET nom_formation = :nom_formation,
                 type = :type,
                 lien = :lien,
                 duree_online = :duree_online,
                 adresse = :adresse,
                 salle = :salle,
                 duree_presentiel = :duree_presentiel,
                 date_debut = :date_debut,
                 date_fin = :date_fin
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
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM `session` WHERE id = :id');
        $statement->execute([':id' => $id]);
    }
}
