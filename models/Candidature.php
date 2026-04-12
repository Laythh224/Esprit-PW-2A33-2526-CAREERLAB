<?php
require_once __DIR__ . '/../config/Database.php';

class Candidature {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function ajouter(array $data): bool {
        $sql = "INSERT INTO Candidature 
                    (offre_id, type_offre, nom, email, telephone, niveau_etudes, motivation, cv_filename, date_candidature)
                VALUES 
                    (:offre_id, :type_offre, :nom, :email, :telephone, :niveau_etudes, :motivation, :cv_filename, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':offre_id'      => $data['offre_id'] ?? null,
            ':type_offre'    => $data['type_offre'],
            ':nom'           => $data['nom'],
            ':email'         => $data['email'],
            ':telephone'     => $data['telephone'] ?? null,
            ':niveau_etudes' => $data['niveau_etudes'] ?? null,
            ':motivation'    => $data['motivation'] ?? null,
            ':cv_filename'   => $data['cv_filename'] ?? null,
        ]);
    }

    public function listerTous(): array {
        $stmt = $this->pdo->query("SELECT * FROM Candidature ORDER BY date_candidature DESC");
        return $stmt->fetchAll();
    }

    public function countByType(): array {
        $stmt = $this->pdo->query("SELECT type_offre, COUNT(*) as total FROM Candidature GROUP BY type_offre");
        return $stmt->fetchAll();
    }
}
?>
