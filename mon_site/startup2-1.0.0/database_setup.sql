-- Script SQL pour créer la base de données et la table des métiers
-- À exécuter dans phpMyAdmin ou via la ligne de commande MySQL

-- Créer la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS gestion_metiers CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utiliser la base de données
USE gestion_metiers;

-- Créer la table metiers
CREATE TABLE IF NOT EXISTS metiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    salaire_moyen DECIMAL(10,2),
    secteur VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer des données de test
INSERT INTO metiers (nom, description, salaire_moyen, secteur) VALUES
('Développeur Web', 'Conception et développement d\'applications web modernes', 35000.00, 'Informatique'),
('Designer Graphique', 'Création de supports visuels et graphiques pour la communication', 28000.00, 'Commerce'),
('Ingénieur Civil', 'Conception et supervision de projets de construction et d\'infrastructure', 45000.00, 'Industrie'),
('Médecin Généraliste', 'Pratique médicale générale et prévention', 55000.00, 'Santé'),
('Professeur des Écoles', 'Enseignement primaire et éducation des enfants', 28000.00, 'Éducation'),
('Comptable', 'Gestion comptable et financière des entreprises', 32000.00, 'Finance'),
('Électricien', 'Installation et maintenance de systèmes électriques', 30000.00, 'Industrie'),
('Chef de Projet Marketing', 'Gestion de campagnes marketing et stratégies commerciales', 42000.00, 'Commerce');

-- Afficher le contenu de la table pour vérification
SELECT * FROM metiers ORDER BY date_creation DESC;