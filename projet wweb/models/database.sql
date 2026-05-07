-- ================================================================
-- Fichier   : database.sql
-- Projet    : Carrer Lab - projet wweb
-- Base      : projet_wweb_db
-- À importer dans : phpMyAdmin > onglet SQL > Exécuter
-- ================================================================

CREATE DATABASE IF NOT EXISTS `projet_wweb_db`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `projet_wweb_db`;

-- ----------------------------------------------------------------
-- Table : OpportuniteTravail
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `OpportuniteTravail` (
    `id`                INT          NOT NULL AUTO_INCREMENT,
    `titre`             VARCHAR(100) NOT NULL,
    `description`       TEXT,
    `entreprise`        VARCHAR(100),
    `localisation`      VARCHAR(100),
    `type_contrat`      VARCHAR(50),
    `date_publication`  DATE,
    `date_expiration`   DATE,
    `niveau_experience` VARCHAR(50),
    `domaine`           VARCHAR(100),
    `travail_id`        INT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table : Stage
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Stage` (
    `id`             INT          NOT NULL AUTO_INCREMENT,
    `duree`          VARCHAR(10),
    `description`    TEXT,
    `nom_societe`    VARCHAR(100) NOT NULL,
    `adresse`        VARCHAR(100),
    `ville`          VARCHAR(100),
    `date_debut`     DATE,
    `date_fin`       DATE,
    `niveau_etude`   VARCHAR(50),
    `email_contact`  VARCHAR(100),
    `telephone`      VARCHAR(20),
    `opportunite_id` INT,
    `statut`         VARCHAR(20)  DEFAULT 'disponible',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ----------------------------------------------------------------
-- Données de test (optionnel)
-- ----------------------------------------------------------------
INSERT INTO `OpportuniteTravail`
    (titre, description, entreprise, localisation, type_contrat, date_publication, date_expiration, niveau_experience, domaine)
VALUES
    ('Développeur Web PHP', 'Développement d\'applications web en PHP/MySQL', 'TechCo', 'Tunis', 'CDI', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'Junior', 'Informatique'),
    ('Designer UI/UX', 'Création de maquettes et prototypes modernes', 'DesignHub', 'Sfax', 'CDD', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'Intermédiaire', 'Design');

INSERT INTO `Stage`
    (duree, description, nom_societe, adresse, ville, date_debut, date_fin, niveau_etude, email_contact, statut)
VALUES
    ('3 mois', 'Stage en développement logiciel', 'Startup TN', 'Av. Habib Bourguiba', 'Tunis', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'Licence', 'contact@startup.tn', 'disponible'),
    ('6 mois', 'Stage en data science et IA', 'DataLab', 'Zone industrielle', 'Monastir', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 180 DAY), 'Master', 'rh@datalab.tn', 'disponible');
