<?php
// exemple_utilisation.php - Exemples d'utilisation de la classe MetierManager

require_once 'config.php';

echo "<h1>Exemples d'utilisation de l'API PHP CRUD</h1>";

// Initialiser le gestionnaire de métiers
$metierManager = new MetierManager();

echo "<h2>1. Ajouter un nouveau métier</h2>";
$nouveauMetier = $metierManager->ajouterMetier(
    "Data Scientist",
    "Analyste de données et intelligence artificielle",
    50000,
    "Informatique"
);

if ($nouveauMetier) {
    echo "<p style='color: green;'>✅ Nouveau métier ajouté avec succès !</p>";
} else {
    echo "<p style='color: red;'>❌ Erreur lors de l'ajout du métier.</p>";
}

echo "<h2>2. Récupérer tous les métiers</h2>";
$tousLesMetiers = $metierManager->getAllMetiers();
echo "<p>Nombre total de métiers : <strong>" . count($tousLesMetiers) . "</strong></p>";

if (!empty($tousLesMetiers)) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Description</th><th>Salaire</th><th>Secteur</th><th>Date création</th></tr>";
    foreach (array_slice($tousLesMetiers, 0, 5) as $metier) { // Afficher seulement les 5 premiers
        echo "<tr>";
        echo "<td>" . htmlspecialchars($metier['id']) . "</td>";
        echo "<td>" . htmlspecialchars($metier['nom']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($metier['description'] ?? '', 0, 50)) . "...</td>";
        echo "<td>" . ($metier['salaire_moyen'] ? number_format($metier['salaire_moyen'], 2) . ' €' : '-') . "</td>";
        echo "<td>" . htmlspecialchars($metier['secteur'] ?? '') . "</td>";
        echo "<td>" . date('d/m/Y H:i', strtotime($metier['date_creation'])) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>3. Rechercher des métiers</h2>";
$resultatsRecherche = $metierManager->rechercherMetiers("développeur");
echo "<p>Résultats pour 'développeur' : <strong>" . count($resultatsRecherche) . "</strong> résultat(s)</p>";

echo "<h2>4. Récupérer un métier par ID</h2>";
if (!empty($tousLesMetiers)) {
    $premierMetier = $metierManager->getMetierById($tousLesMetiers[0]['id']);
    if ($premierMetier) {
        echo "<p>Métier trouvé : <strong>" . htmlspecialchars($premierMetier['nom']) . "</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ Métier non trouvé.</p>";
    }
}

echo "<h2>5. Modifier un métier</h2>";
if (!empty($tousLesMetiers)) {
    $metierAModifier = end($tousLesMetiers); // Dernier métier ajouté
    $modification = $metierManager->modifierMetier(
        $metierAModifier['id'],
        $metierAModifier['nom'] . " (Modifié)",
        $metierAModifier['description'],
        $metierAModifier['salaire_moyen'],
        $metierAModifier['secteur']
    );

    if ($modification) {
        echo "<p style='color: green;'>✅ Métier modifié avec succès !</p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur lors de la modification.</p>";
    }
}

echo "<h2>6. Utilisation dans un formulaire HTML</h2>";
echo "<p>Voici comment utiliser l'API dans vos formulaires :</p>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
// Exemple de code PHP pour formulaire
echo htmlspecialchars('<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metierManager = new MetierManager();

    if (isset($_POST["ajouter"])) {
        $metierManager->ajouterMetier(
            $_POST["nom"],
            $_POST["description"],
            $_POST["salaire"],
            $_POST["secteur"]
        );
    } elseif (isset($_POST["modifier"])) {
        $metierManager->modifierMetier(
            $_POST["id"],
            $_POST["nom"],
            $_POST["description"],
            $_POST["salaire"],
            $_POST["secteur"]
        );
    } elseif (isset($_POST["supprimer"])) {
        $metierManager->supprimerMetier($_POST["id"]);
    }
}
?>');
echo "</pre>";

echo "<hr>";
echo "<p><a href='metiers.php' class='btn btn-primary'>Accéder à l'interface web</a></p>";
echo "<p><a href='test_connection.php' class='btn btn-secondary'>Tester la connexion</a></p>";
?>