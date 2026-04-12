<?php
// config.php - Connexion à la base de données
$host = 'localhost';
$dbname = 'projet_wweb_db'; // Change ce nom si ta base a un autre nom
$username = 'root'; // Utilisateur par défaut de XAMPP
$password = ''; // Pas de mot de passe par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurer PDO pour qu'il lève des exceptions en cas d'erreur SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
