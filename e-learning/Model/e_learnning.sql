-- Base de donnees : `e_learnning`
-- Schema cible : formation + critere

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `planning_option`;
DROP TABLE IF EXISTS `planning`;
DROP TABLE IF EXISTS `optionn`;
DROP TABLE IF EXISTS `critere`;
DROP TABLE IF EXISTS `formation`;

CREATE TABLE `formation` (
  `nom_formation` VARCHAR(150) NOT NULL,
  `specialite` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `date_debut` DATE NOT NULL,
  `date_fin` DATE NOT NULL,
  `niveau` VARCHAR(80) NOT NULL,
  `duree` INT NOT NULL,
  PRIMARY KEY (`nom_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `critere` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom_formation` VARCHAR(150) NOT NULL,
  `type` ENUM('online', 'presentiel') NOT NULL,
  `lien` VARCHAR(255) DEFAULT NULL,
  `duree_online` INT DEFAULT NULL,
  `adresse` VARCHAR(255) DEFAULT NULL,
  `salle` VARCHAR(120) DEFAULT NULL,
  `duree_presentiel` INT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_critere_formation` (`nom_formation`),
  CONSTRAINT `fk_critere_formation`
    FOREIGN KEY (`nom_formation`) REFERENCES `formation` (`nom_formation`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
