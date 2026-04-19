<?php
require_once __DIR__ . '/../config/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    echo "Démarrage de la migration...\n";

    // 1. Créer la table Experience
    $sqlCreateExp = "CREATE TABLE IF NOT EXISTS Experience (
        id INT AUTO_INCREMENT PRIMARY KEY,
        niveau VARCHAR(50) NOT NULL,
        description VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $pdo->exec($sqlCreateExp);
    echo "Table 'Experience' créée ou déjà existante.\n";

    // 2. Insérer les niveaux par défaut si la table est vide
    $count = $pdo->query("SELECT COUNT(*) FROM Experience")->fetchColumn();
    if ($count == 0) {
        $sqlInsert = "INSERT INTO Experience (niveau, description) VALUES 
            ('Junior', '0-2 ans d\'expérience'),
            ('Intermédiaire', '2-5 ans d\'expérience'),
            ('Senior', '5-10 ans d\'expérience'),
            ('Expert', 'Plus de 10 ans d\'expérience')";
        $pdo->exec($sqlInsert);
        echo "Niveaux d'expérience par défaut insérés.\n";
    }

    // 3. Modifier la table OpportuniteTravail
    // Vérifier si la colonne experience_id existe déjà
    $columns = $pdo->query("DESCRIBE OpportuniteTravail")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('experience_id', $columns)) {
        $pdo->exec("ALTER TABLE OpportuniteTravail ADD COLUMN experience_id INT AFTER type_contrat");
        $pdo->exec("ALTER TABLE OpportuniteTravail ADD CONSTRAINT fk_experience_id FOREIGN KEY (experience_id) REFERENCES Experience(id) ON DELETE SET NULL");
        echo "Colonne 'experience_id' ajoutée à 'OpportuniteTravail'.\n";
    }

    // 4. Migration des données existantes (Tentative de mapping)
    if (in_array('niveau_experience', $columns)) {
        echo "Tentative de migration des données de 'niveau_experience' vers 'experience_id'...\n";
        $experiences = $pdo->query("SELECT id, niveau FROM Experience")->fetchAll();
        foreach ($experiences as $exp) {
            $stmt = $pdo->prepare("UPDATE OpportuniteTravail SET experience_id = :eid WHERE niveau_experience LIKE :niveau");
            $stmt->execute([':eid' => $exp['id'], ':niveau' => '%' . $exp['niveau'] . '%']);
        }
        
        // Supprimer l'ancienne colonne (Optionnel : On pourrait la garder en backup, mais le user demande "je veux quelle soix comme ca")
        // $pdo->exec("ALTER TABLE OpportuniteTravail DROP COLUMN niveau_experience");
        // echo "Ancienne colonne 'niveau_experience' supprimée.\n";
    }

    echo "Migration terminée avec succès !\n";

} catch (Exception $e) {
    die("ERREUR lors de la migration : " . $e->getMessage() . "\n");
}
?>
