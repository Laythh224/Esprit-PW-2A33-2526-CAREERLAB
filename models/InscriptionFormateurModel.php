<?php

class InscriptionFormateurModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function all(): array
    {
        // Double Jointure pour recupérer l'Utilisateur ET le Formateur
        $sql = "SELECT ifor.id_user, ifor.id_formateur, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email, f.nom as formateur_nom, f.prenom as formateur_prenom, f.email as formateur_email
                FROM inscription_formateur ifor
                INNER JOIN utilisateur u ON ifor.id_user = u.id
                INNER JOIN formateur f ON ifor.id_formateur = f.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public function create(int $id_user, int $id_formateur): void
    {
        if ($this->exists($id_user, $id_formateur)) {
            throw new RuntimeException("Cette inscription existe deja.");
        }
        
        $stmt = $this->conn->prepare('INSERT INTO inscription_formateur (id_user, id_formateur) VALUES (?, ?)');
        $stmt->execute([$id_user, $id_formateur]);
    }

    public function delete(int $id_user, int $id_formateur): void
    {
        $stmt = $this->conn->prepare('DELETE FROM inscription_formateur WHERE id_user = ? AND id_formateur = ?');
        $stmt->execute([$id_user, $id_formateur]);
    }

    public function exists(int $id_user, int $id_formateur): bool
    {
        $stmt = $this->conn->prepare('SELECT 1 FROM inscription_formateur WHERE id_user = ? AND id_formateur = ? LIMIT 1');
        $stmt->execute([$id_user, $id_formateur]);
        return $stmt->fetchColumn() !== false;
    }
}
