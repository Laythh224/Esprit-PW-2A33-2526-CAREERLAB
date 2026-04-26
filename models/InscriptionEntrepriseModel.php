<?php

class InscriptionEntrepriseModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function all(): array
    {
        // Double Jointure pour recupérer l'Utilisateur ET l'Entreprise
        $sql = "SELECT ie.id_user, ie.id_entreprise, u.nom, u.prenom, u.email as user_email, e.nom_entreprise, e.email as entreprise_email
                FROM inscription_entreprise ie
                INNER JOIN utilisateur u ON ie.id_user = u.id
                INNER JOIN entreprise e ON ie.id_entreprise = e.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public function create(int $id_user, int $id_entreprise): void
    {
        if ($this->exists($id_user, $id_entreprise)) {
            throw new RuntimeException("Cette inscription existe deja.");
        }
        
        $stmt = $this->conn->prepare('INSERT INTO inscription_entreprise (id_user, id_entreprise) VALUES (?, ?)');
        $stmt->execute([$id_user, $id_entreprise]);
    }

    public function delete(int $id_user, int $id_entreprise): void
    {
        $stmt = $this->conn->prepare('DELETE FROM inscription_entreprise WHERE id_user = ? AND id_entreprise = ?');
        $stmt->execute([$id_user, $id_entreprise]);
    }

    public function exists(int $id_user, int $id_entreprise): bool
    {
        $stmt = $this->conn->prepare('SELECT 1 FROM inscription_entreprise WHERE id_user = ? AND id_entreprise = ? LIMIT 1');
        $stmt->execute([$id_user, $id_entreprise]);
        return $stmt->fetchColumn() !== false;
    }
}
