<?php
require_once __DIR__ . '/../config/Database.php';

class OpportuniteTravail {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function ajouter(array $data): bool {
        $sql = "INSERT INTO OpportuniteTravail 
                    (titre, description, entreprise, localisation, type_contrat, 
                     date_publication, date_expiration, niveau_experience, domaine, travail_id)
                VALUES 
                    (:titre, :description, :entreprise, :localisation, :type_contrat,
                     :date_publication, :date_expiration, :niveau_experience, :domaine, :travail_id)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titre'             => $data['titre'],
            ':description'       => $data['description'] ?? null,
            ':entreprise'        => $data['entreprise'] ?? null,
            ':localisation'      => $data['localisation'] ?? null,
            ':type_contrat'      => $data['type_contrat'] ?? null,
            ':date_publication'  => !empty($data['date_publication']) ? $data['date_publication'] : null,
            ':date_expiration'   => !empty($data['date_expiration']) ? $data['date_expiration'] : null,
            ':niveau_experience' => $data['niveau_experience'] ?? null,
            ':domaine'           => $data['domaine'] ?? null,
            ':travail_id'        => !empty($data['travail_id']) ? $data['travail_id'] : null,
        ]);
    }

    public function listerTous(): array {
        $stmt = $this->pdo->query("SELECT * FROM OpportuniteTravail ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM OpportuniteTravail WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function modifier(int $id, array $data): bool {
        $sql = "UPDATE OpportuniteTravail SET 
                    titre = :titre, 
                    description = :description, 
                    entreprise = :entreprise, 
                    localisation = :localisation, 
                    type_contrat = :type_contrat, 
                    date_expiration = :date_expiration, 
                    niveau_experience = :niveau_experience, 
                    domaine = :domaine
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'                => $id,
            ':titre'             => $data['titre'],
            ':description'       => $data['description'] ?? null,
            ':entreprise'        => $data['entreprise'] ?? null,
            ':localisation'      => $data['localisation'] ?? null,
            ':type_contrat'      => $data['type_contrat'] ?? null,
            ':date_expiration'   => !empty($data['date_expiration']) ? $data['date_expiration'] : null,
            ':niveau_experience' => $data['niveau_experience'] ?? null,
            ':domaine'           => $data['domaine'] ?? null,
        ]);
    }

    public function supprimer(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM OpportuniteTravail WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
