<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic - Gestion des Métiers</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-stethoscope me-2"></i>Diagnostic de l'application
                        </h3>
                    </div>
                    <div class="card-body">
                        <h4>Test de connexion à la base de données</h4>
                        <?php
                        require_once 'config.php';

                        echo "<div class='mb-4'>";

                        // Test 1: Connexion à la base de données
                        echo "<h5><i class='fas fa-database me-2'></i>Test de connexion MySQL</h5>";
                        try {
                            $pdo = getDBConnection();
                            echo "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Connexion réussie à la base de données '" . DB_NAME . "'</div>";

                            // Test 2: Création de la table
                            echo "<h5><i class='fas fa-table me-2'></i>Test de création de table</h5>";
                            createTableIfNotExists();
                            echo "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Table 'metiers' créée/vérifiée</div>";

                            // Test 3: Vérification du nombre d'enregistrements
                            echo "<h5><i class='fas fa-list me-2'></i>État des données</h5>";
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM metiers");
                            $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                            echo "<div class='alert alert-info'><i class='fas fa-info-circle me-2'></i>Nombre total de métiers dans la base : <strong>$count</strong></div>";

                            if ($count > 0) {
                                // Afficher quelques métiers
                                echo "<h6>Derniers métiers ajoutés :</h6>";
                                $stmt = $pdo->query("SELECT * FROM metiers ORDER BY date_creation DESC LIMIT 3");
                                $metiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                echo "<ul class='list-group mb-3'>";
                                foreach ($metiers as $metier) {
                                    echo "<li class='list-group-item'>";
                                    echo "<strong>" . htmlspecialchars($metier['nom']) . "</strong>";
                                    if ($metier['secteur']) {
                                        echo " <span class='badge bg-primary'>" . htmlspecialchars($metier['secteur']) . "</span>";
                                    }
                                    if ($metier['salaire_moyen']) {
                                        echo " <span class='badge bg-success'>" . number_format($metier['salaire_moyen'], 0, ',', ' ') . " €</span>";
                                    }
                                    echo "<br><small class='text-muted'>Ajouté le " . date('d/m/Y H:i', strtotime($metier['date_creation'])) . "</small>";
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }

                            // Test 4: Test d'ajout d'un métier temporaire
                            echo "<h5><i class='fas fa-plus me-2'></i>Test d'ajout temporaire</h5>";
                            $testNom = "Test Diagnostic " . date('His');
                            $stmt = $pdo->prepare("INSERT INTO metiers (nom, description, secteur) VALUES (?, ?, ?)");
                            $result = $stmt->execute([$testNom, "Métier de test pour diagnostic", "Test"]);

                            if ($result) {
                                $testId = $pdo->lastInsertId();
                                echo "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Métier de test ajouté avec succès (ID: $testId)</div>";

                                // Supprimer le test
                                $stmt = $pdo->prepare("DELETE FROM metiers WHERE id = ?");
                                $stmt->execute([$testId]);
                                echo "<div class='alert alert-info'><i class='fas fa-trash me-2'></i>Métier de test supprimé</div>";
                            } else {
                                echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle me-2'></i>Échec de l'ajout du métier de test</div>";
                            }

                        } catch (PDOException $e) {
                            echo "<div class='alert alert-danger'><i class='fas fa-times-circle me-2'></i>Erreur de base de données : " . htmlspecialchars($e->getMessage()) . "</div>";

                            // Suggestions de dépannage
                            echo "<div class='alert alert-info mt-3'>";
                            echo "<h6>💡 Suggestions de dépannage :</h6>";
                            echo "<ul class='mb-0'>";
                            echo "<li>Vérifiez que MySQL est installé et démarré</li>";
                            echo "<li>Créez la base de données : <code>CREATE DATABASE gestion_metiers;</code></li>";
                            echo "<li>Vérifiez les paramètres de connexion dans <code>config.php</code></li>";
                            echo "<li>Assurez-vous que l'utilisateur MySQL a les droits nécessaires</li>";
                            echo "</ul>";
                            echo "</div>";
                        }

                        echo "</div>";
                        ?>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fas fa-home me-1"></i>Retour à l'accueil
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="database_setup.sql" class="btn btn-outline-secondary" target="_blank">
                                    <i class="fas fa-file-code me-1"></i>Voir le script SQL
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>