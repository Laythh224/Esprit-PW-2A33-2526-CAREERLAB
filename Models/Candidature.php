<?php

class Candidature {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connection();
    }

    public function create($data) {
        $sql = "INSERT INTO Candidature 
                (offre_id, nom_candidat, email_candidat, cv_texte, score_test, score_ia, compatibilite, niveau, recommandation, feedback) 
                VALUES 
                (:offre_id, :nom, :email, :cv, :test, :score, :comp, :niv, :rec, :feed)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getByOffre($offre_id) {
        $stmt = $this->db->prepare("SELECT * FROM Candidature WHERE offre_id = :offre_id ORDER BY score_ia DESC");
        $stmt->execute([':offre_id' => $offre_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        return $this->db->query("SELECT c.*, o.titre as offre_titre FROM Candidature c JOIN OpportuniteTravail o ON c.offre_id = o.id ORDER BY c.date_postulation DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Candidature WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
