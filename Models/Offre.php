<?php

class Offre {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connection();
    }

    public function getAll($search = '', $sort = 'newest') {
        $sql = "SELECT t.*, e.niveau AS niveau_experience 
                FROM OpportuniteTravail t 
                LEFT JOIN Experience e ON t.experience_id = e.id 
                WHERE 1=1";
        
        if ($search) {
            $sql .= " AND (t.titre LIKE :q OR t.entreprise LIKE :q OR t.domaine LIKE :q OR t.localisation LIKE :q)";
        }
        
        switch ($sort) {
            case 'title_asc': $sql .= " ORDER BY t.titre ASC"; break;
            case 'oldest': $sql .= " ORDER BY t.id ASC"; break;
            case 'newest': default: $sql .= " ORDER BY t.id DESC"; break;
        }

        $stmt = $this->db->prepare($sql);
        if ($search) {
            $stmt->bindValue(':q', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT t.*, e.niveau AS niveau_experience 
                FROM OpportuniteTravail t 
                LEFT JOIN Experience e ON t.experience_id = e.id 
                WHERE t.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO OpportuniteTravail 
                (titre, description, entreprise, localisation, type_contrat, date_expiration, experience_id, domaine)
                VALUES 
                (:titre, :description, :entreprise, :localisation, :type_contrat, :date_expiration, :experience_id, :domaine)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $data[':id'] = $id;
        $sql = "UPDATE OpportuniteTravail SET 
                titre=:titre, description=:description, entreprise=:entreprise, 
                localisation=:localisation, type_contrat=:type_contrat, 
                date_expiration=:date_expiration, experience_id=:experience_id, domaine=:domaine 
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM OpportuniteTravail WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getExperiences() {
        return $this->db->query("SELECT * FROM Experience ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createExperience($data) {
        $sql = "INSERT INTO Experience (nom, prenom, niveau, description) VALUES (:nom, :prenom, :niveau, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteExperience($id) {
        $stmt = $this->db->prepare("DELETE FROM Experience WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
