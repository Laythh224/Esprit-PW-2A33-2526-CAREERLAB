<?php
require_once __DIR__ . '/../config/Database.php';

class Stage {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function ajouter(array $data): bool {
        $sql = "INSERT INTO Stage 
                    (duree, description, nom_societe, adresse, ville,
                     date_debut, date_fin, niveau_etude, email_contact,
                     telephone, opportunite_id, statut)
                VALUES 
                    (:duree, :description, :nom_societe, :adresse, :ville,
                     :date_debut, :date_fin, :niveau_etude, :email_contact,
                     :telephone, :opportunite_id, :statut)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':duree'         => $data['duree'] ?? null,
            ':description'   => $data['description'] ?? null,
            ':nom_societe'   => $data['nom_societe'],
            ':adresse'       => $data['adresse'] ?? null,
            ':ville'         => $data['ville'] ?? null,
            ':date_debut'    => !empty($data['date_debut']) ? $data['date_debut'] : null,
            ':date_fin'      => !empty($data['date_fin']) ? $data['date_fin'] : null,
            ':niveau_etude'  => $data['niveau_etude'] ?? null,
            ':email_contact' => $data['email_contact'] ?? null,
            ':telephone'     => $data['telephone'] ?? null,
            ':opportunite_id'=> !empty($data['opportunite_id']) ? $data['opportunite_id'] : null,
            ':statut'        => $data['statut'] ?? 'disponible',
        ]);
    }

    public function listerTous(): array {
        $stmt = $this->pdo->query("SELECT * FROM Stage ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Stage WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function modifier(int $id, array $data): bool {
        $sql = "UPDATE Stage SET 
                    duree = :duree, 
                    description = :description, 
                    nom_societe = :nom_societe, 
                    adresse = :adresse, 
                    ville = :ville,
                    date_debut = :date_debut, 
                    date_fin = :date_fin, 
                    niveau_etude = :niveau_etude, 
                    email_contact = :email_contact,
                    telephone = :telephone, 
                    statut = :statut
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':duree'         => $data['duree'] ?? null,
            ':description'   => $data['description'] ?? null,
            ':nom_societe'   => $data['nom_societe'],
            ':adresse'       => $data['adresse'] ?? null,
            ':ville'         => $data['ville'] ?? null,
            ':date_debut'    => !empty($data['date_debut']) ? $data['date_debut'] : null,
            ':date_fin'      => !empty($data['date_fin']) ? $data['date_fin'] : null,
            ':niveau_etude'  => $data['niveau_etude'] ?? null,
            ':email_contact' => $data['email_contact'] ?? null,
            ':telephone'     => $data['telephone'] ?? null,
            ':statut'        => $data['statut'] ?? 'disponible',
        ]);
    }

    public function supprimer(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Stage WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
