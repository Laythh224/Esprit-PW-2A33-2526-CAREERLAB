<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_offre = $_POST['type_offre'] ?? '';

    if ($type_offre === 'travail') {
        // Récupération des champs pour l'opportunité de travail
        $id = !empty($_POST['id']) ? $_POST['id'] : null;
        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $entreprise = trim($_POST['entreprise']);
        $localisation = trim($_POST['localisation']);
        $type_contrat = trim($_POST['type_contrat']);
        $niveau_experience = trim($_POST['niveau_experience']);
        
        $date_publication = !empty($_POST['date_publication']) ? $_POST['date_publication'] : null;
        $date_expiration = !empty($_POST['date_expiration']) ? $_POST['date_expiration'] : null;
        $domaine = trim($_POST['domaine']);
        $travail_id = !empty($_POST['travail_id']) ? $_POST['travail_id'] : null;

        $sql = "INSERT INTO OpportuniteTravail (id, titre, description, entreprise, localisation, type_contrat, date_publication, date_expiration, niveau_experience, domaine, travail_id)
                VALUES (:id, :titre, :description, :entreprise, :localisation, :type_contrat, :date_publication, :date_expiration, :niveau_experience, :domaine, :travail_id)";
        
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':id' => $id,
                ':titre' => $titre,
                ':description' => $description,
                ':entreprise' => $entreprise,
                ':localisation' => $localisation,
                ':type_contrat' => $type_contrat,
                ':date_publication' => $date_publication,
                ':date_expiration' => $date_expiration,
                ':niveau_experience' => $niveau_experience,
                ':domaine' => $domaine,
                ':travail_id' => $travail_id
            ]);
            echo "<script>alert('Offre de travail publiée avec succès !'); window.location.href='testimonial.html';</script>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }

    } elseif ($type_offre === 'stage') {
        // Récupération des champs pour le stage
        $id = !empty($_POST['id']) ? $_POST['id'] : null;
        $nom_societe = trim($_POST['nom_societe']);
        $description = trim($_POST['description']);
        $adresse = trim($_POST['adresse']);
        $ville = trim($_POST['ville']);
        
        $date_debut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $date_fin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
        $duree = trim($_POST['duree']);
        $niveau_etude = trim($_POST['niveau_etude']);
        $statut = trim($_POST['statut']);
        $email_contact = trim($_POST['email_contact']);
        $telephone = trim($_POST['telephone']);
        $opportunite_id = !empty($_POST['opportunite_id']) ? $_POST['opportunite_id'] : null;

        $sql = "INSERT INTO Stage (id, duree, description, nom_societe, adresse, ville, date_debut, date_fin, niveau_etude, email_contact, telephone, opportunite_id, statut)
                VALUES (:id, :duree, :description, :nom_societe, :adresse, :ville, :date_debut, :date_fin, :niveau_etude, :email_contact, :telephone, :opportunite_id, :statut)";
        
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':id' => $id,
                ':duree' => $duree,
                ':description' => $description,
                ':nom_societe' => $nom_societe,
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':niveau_etude' => $niveau_etude,
                ':email_contact' => $email_contact,
                ':telephone' => $telephone,
                ':opportunite_id' => $opportunite_id,
                ':statut' => $statut
            ]);
            echo "<script>alert('Stage publié avec succès !'); window.location.href='testimonial.html';</script>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
} else {
    header("Location: testimonial.html");
    exit();
}
?>
