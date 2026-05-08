-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 07 mai 2026 à 23:20
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `plateforme_unique`
--

-- --------------------------------------------------------

--
-- Structure de la table `account_verification_logs`
--

CREATE TABLE `account_verification_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_type` varchar(32) DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `previous_verified` tinyint(1) DEFAULT NULL,
  `new_verified` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE `candidature` (
  `id` int(11) NOT NULL,
  `offre_id` int(11) DEFAULT NULL,
  `date_postulation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(20) DEFAULT 'en_attente',
  `nom_candidat` varchar(255) DEFAULT NULL,
  `email_candidat` varchar(255) DEFAULT NULL,
  `cv_texte` text DEFAULT NULL,
  `score_test` int(11) DEFAULT NULL,
  `score_ia` int(11) DEFAULT NULL,
  `compatibilite` int(11) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `recommandation` varchar(50) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `candidature`
--

INSERT INTO `candidature` (`id`, `offre_id`, `date_postulation`, `statut`, `nom_candidat`, `email_candidat`, `cv_texte`, `score_test`, `score_ia`, `compatibilite`, `niveau`, `recommandation`, `feedback`) VALUES
(1, 2, '2026-05-07 15:26:43', 'en_attente', 'sami', 'famey37991@bablace.com', 'diplome autocade', 4, 50, 25, 'Intermédiaire', 'À revoir', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Mentionnez vos années d\'expérience (ex: \'8 ans\').</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Diplôme(s) mentionné(s) dans le CV.</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Bon score au test technique (4/5).</span><br></div>'),
(2, 3, '2026-05-07 15:33:32', 'refuse', 's', 'jacem.barkallah@esprit.tn', 'ans diplome', 3, 40, 20, 'Intermédiaire', 'À revoir', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Mentionnez vos années d\'expérience (ex: \'8 ans\').</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Diplôme(s) mentionné(s) dans le CV.</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Bon score au test technique (3/5).</span><br></div>'),
(3, 2, '2026-05-07 15:34:28', 'en_attente', 'si jacem', 'jacem.barkallah@esprit.tn', 'Automatisme, Thermodynamique, Hydraulique, Maintenance,\r\n8 ans avec deux diplomes', 4, 100, 50, 'Avancé', 'À revoir', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Compétences détectées : Automatisme, Thermodynamique, Hydraulique, Maintenance</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Expérience détectée : 8 an(s)</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Précisez vos diplômes (Licence, Master...).</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Bon score au test technique (4/5).</span><br></div>'),
(4, 4, '2026-05-07 15:52:36', 'accepte', 'si jacem', 'jacem.barkallah@esprit.tn', '8ans', 2, 60, 30, 'Intermédiaire', 'À revoir', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Expérience détectée : 8 an(s)</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Précisez vos diplômes (Licence, Master...).</span><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Score faible au test technique (2/5).</span><br></div>'),
(5, 5, '2026-05-07 16:49:42', 'en_attente', 'sami', 'jacembarkallah05@gmail.com', 's', 1, 10, 5, 'Débutant', 'Refuser', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Mentionnez vos années d\'expérience (ex: \'8 ans\').</span><br><span class=\'text-warning\'><i class=\'fas fa-exclamation-triangle\'></i> Suggestion : Précisez vos diplômes (Licence, Master...).</span><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Score faible au test technique (1/5).</span><br></div>'),
(6, 6, '2026-05-07 17:53:44', 'accepte', 'sami', 'jacembarkallah05@gmail.com', 'diplome 8ans', 2, 70, 35, 'Intermédiaire', 'À revoir', '<div class=\'text-start\'><strong>Analyse IA du profil :</strong><br><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Expérience détectée : 8 an(s)</span><br><span class=\'text-success\'><i class=\'fas fa-check-circle\'></i> Diplôme(s) mentionné(s) dans le CV.</span><br><span class=\'text-danger\'><i class=\'fas fa-times-circle\'></i> Score faible au test technique (2/5).</span><br></div>');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`) VALUES
(1, 'Technologie', '2026-05-07 14:43:24'),
(2, 'Santé', '2026-05-07 14:43:24'),
(3, 'Finance', '2026-05-07 14:43:24'),
(4, 'Éducation', '2026-05-07 14:43:24'),
(5, 'Commerce', '2026-05-07 14:43:24'),
(6, 'Industrie', '2026-05-07 14:43:24');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `nom_entreprise` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'entreprise',
  `telephone` varchar(20) DEFAULT NULL,
  `code_fiscal` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `site_web` varchar(100) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id`, `nom_entreprise`, `email`, `password`, `role`, `telephone`, `code_fiscal`, `ville`, `secteur`, `adresse`, `description`, `site_web`, `cv`, `email_verified`, `verified`, `verified_at`, `verified_by`, `created_at`) VALUES
(1, 'bono bango', '5on9oku05@gmail.com', '$2y$10$aBXtUhVictllLdizEBUTZuoHY40iQo7NusHXjQS4vbJDqLXvqM7Ba', 'entreprise', '+21654619393', '11111111111', 'tunis', 'Autre', 'la ghazelle', 'dsssssssssssssssssssssssssssssssssssssss', '', '', 1, 0, NULL, NULL, '2026-05-07 17:19:29'),
(2, 'sigula', 'jacembarkallah05@gmail.com', '$2y$10$RWZfzCHogzyWg2E6.tiOH.RWoj9QmpC8EyxTGKueKnN98ujp8iy6S', 'entreprise', '+21654619393', '11111111111', 'tunis', 'Autre', 'la ghazelle', 'sssssssssssssssssssssssssssssssssss', '', '', 1, 0, NULL, NULL, '2026-05-07 18:32:59');

-- --------------------------------------------------------

--
-- Structure de la table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `experience`
--

INSERT INTO `experience` (`id`, `nom`, `prenom`, `niveau`, `description`) VALUES
(1, 'z', 'z', 'Intermédiaire', 'qss'),
(3, 's', 's', 'Expert', 's');

-- --------------------------------------------------------

--
-- Structure de la table `formateur`
--

CREATE TABLE `formateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `sexe` varchar(20) DEFAULT NULL,
  `specialite` varchar(100) DEFAULT NULL,
  `diplomes` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `nom_formation` varchar(150) NOT NULL,
  `specialite` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `niveau` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`nom_formation`, `specialite`, `description`, `niveau`) VALUES
('communication professionelle', 'soft skills', 'Prise de parole et feedback en équipe', 'bac +2'),
('Découverte IA', 'Data', 'Introduction aux usages de l\'intelligence artificielle', 'bac+5'),
('developement web', 'front end', 'html/css', 'licence'),
('securite', 'cloud', 'fqscd', 'bac'),
('securiteee', 'cloud', 'fgtyhuiop', 'bac');

-- --------------------------------------------------------

--
-- Structure de la table `inscription_entreprise`
--

CREATE TABLE `inscription_entreprise` (
  `id_user` int(11) NOT NULL,
  `id_entreprise` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription_formateur`
--

CREATE TABLE `inscription_formateur` (
  `id_user` int(11) NOT NULL,
  `id_formateur` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `competences` text DEFAULT NULL,
  `specialites` text DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `job`
--

INSERT INTO `job` (`id`, `title`, `description`, `competences`, `specialites`, `salaire`, `category_id`, `views`, `session_id`, `created_at`) VALUES
(1, 'Développeur Web', 'Conception et développement d\'applications web modernes', NULL, NULL, 35000.00, 1, 0, NULL, '2026-05-07 14:43:24'),
(2, 'Designer Graphique', 'Création de supports visuels et graphiques', NULL, NULL, 28000.00, 5, 0, NULL, '2026-05-07 14:43:24'),
(3, 'Ingénieur Civil', 'Conception et supervision de projets de construction', NULL, NULL, 45000.00, 6, 0, NULL, '2026-05-07 14:43:24'),
(4, 'Médecin Généraliste', 'Pratique médicale générale et prévention', NULL, NULL, 55000.00, 2, 0, NULL, '2026-05-07 14:43:24'),
(5, 'Professeur des Écoles', 'Enseignement primaire et éducation des enfants', NULL, NULL, 28000.00, 4, 0, NULL, '2026-05-07 14:43:24'),
(6, 'Comptable', 'Gestion comptable et financière des entreprises', NULL, NULL, 32000.00, 3, 0, NULL, '2026-05-07 14:43:24');

-- --------------------------------------------------------

--
-- Structure de la table `opportunitetravail`
--

CREATE TABLE `opportunitetravail` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `localisation` varchar(100) DEFAULT NULL,
  `type_contrat` varchar(50) DEFAULT NULL,
  `experience_id` int(11) DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `domaine` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `opportunitetravail`
--

INSERT INTO `opportunitetravail` (`id`, `titre`, `description`, `entreprise`, `localisation`, `type_contrat`, `experience_id`, `date_expiration`, `domaine`) VALUES
(1, 'validation', 'zssss', 'apo', 'sousse', 'cdd', 1, '2026-05-30', 'economies'),
(3, 'validation', 's', 'apos', 'sousse', 'cdi', NULL, '2026-05-10', 'architecture'),
(4, 'validation', 'd', 'apos', 'sousse', 'cdi', 1, '2026-05-10', 'informatique'),
(5, 'sssssssss', 's', 'apos', 'sousse', 'cdi', 3, '2026-05-10', 'economie'),
(6, 'qqqqqqq', 'aaaaaaaaaaaa', 'sigula', 'sousse', 'cdi', 3, '2026-05-10', 'architecture');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `texte` text DEFAULT NULL,
  `id_metier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `texte`, `id_metier`) VALUES
(1, 'y5y jacem beau gosss ?', 5),
(2, 'y5y jacem mezyen?', 5),
(3, 'y5y jacem mahleh?', 5);

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

CREATE TABLE `reponses` (
  `id` int(11) NOT NULL,
  `texte` text DEFAULT NULL,
  `est_correcte` tinyint(1) DEFAULT NULL,
  `id_question` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `texte`, `est_correcte`, `id_question`) VALUES
(1, 'ay ay bien sur', 1, 1),
(2, 'ouiii betbi3a', 1, 2),
(3, 'ouiuiiiii', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reponses_utilisateurs`
--

CREATE TABLE `reponses_utilisateurs` (
  `id` int(11) NOT NULL,
  `id_question` int(11) DEFAULT NULL,
  `id_reponse` int(11) DEFAULT NULL,
  `attempt_token` varchar(64) DEFAULT NULL,
  `est_correcte` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponses_utilisateurs`
--

INSERT INTO `reponses_utilisateurs` (`id`, `id_question`, `id_reponse`, `attempt_token`, `est_correcte`, `created_at`) VALUES
(1, 1, 1, 'attempt_69fcd5a5828f5496050428', 1, NULL),
(2, 2, 2, 'attempt_69fcd5a5828f5496050428', 1, NULL),
(3, 3, 3, 'attempt_69fcd5a5828f5496050428', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `nom_formation` varchar(150) NOT NULL,
  `type` enum('online','presentiel') NOT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `duree_online` int(11) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `salle` varchar(120) DEFAULT NULL,
  `duree_presentiel` int(11) DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nb_place` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`id`, `nom_formation`, `type`, `lien`, `duree_online`, `adresse`, `salle`, `duree_presentiel`, `date_debut`, `date_fin`, `nb_place`) VALUES
(1, 'communication professionelle', 'online', 'http://dgfdg/fds', 12, NULL, NULL, NULL, '2026-05-09', '2026-05-14', 6),
(2, 'Découverte IA', 'online', 'http://dgfdg/fds', 2, NULL, NULL, NULL, '2026-05-15', '2026-05-22', 14),
(3, 'securite', 'presentiel', NULL, NULL, 'dddd', 'a33', 11, '2026-05-27', '2026-06-17', 0),
(4, 'securite', 'online', 'http://dgfdg/fds', 2, NULL, NULL, NULL, '2026-05-27', '2026-05-31', 7);

-- --------------------------------------------------------

--
-- Structure de la table `test`
--

CREATE TABLE `test` (
  `id_test` int(11) NOT NULL,
  `test_token` varchar(64) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_metier` int(11) NOT NULL,
  `id_question` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `test`
--

INSERT INTO `test` (`id_test`, `test_token`, `user_name`, `user_email`, `date`, `heure_debut`, `heure_fin`, `id_metier`, `id_question`) VALUES
(1, '3a8742d6d7c9e558ec32d4c78be83751', 'jjj', 'jacembarkallah05@gmail.com', '2026-05-23', '11:11:00', '16:33:00', 5, 1),
(2, '3a8742d6d7c9e558ec32d4c78be83751', 'jjj', 'jacembarkallah05@gmail.com', '2026-05-23', '11:11:00', '16:33:00', 5, 2),
(3, '3a8742d6d7c9e558ec32d4c78be83751', 'jjj', 'jacembarkallah05@gmail.com', '2026-05-23', '11:11:00', '16:33:00', 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `telephone` varchar(20) DEFAULT NULL,
  `sexe` varchar(20) DEFAULT NULL,
  `niveau_etude` varchar(50) DEFAULT NULL,
  `domaine` varchar(100) DEFAULT NULL,
  `competences` text DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int(10) UNSIGNED DEFAULT NULL,
  `email_verification_code` varchar(10) DEFAULT NULL,
  `email_verification_expires_at` datetime DEFAULT NULL,
  `email_verification_attempts` int(11) DEFAULT 0,
  `email_verification_sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `account_verification_logs`
--
ALTER TABLE `account_verification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formateur`
--
ALTER TABLE `formateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `inscription_entreprise`
--
ALTER TABLE `inscription_entreprise`
  ADD PRIMARY KEY (`id_user`,`id_entreprise`);

--
-- Index pour la table `inscription_formateur`
--
ALTER TABLE `inscription_formateur`
  ADD PRIMARY KEY (`id_user`,`id_formateur`);

--
-- Index pour la table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `opportunitetravail`
--
ALTER TABLE `opportunitetravail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `experience_id` (`experience_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_question` (`id_question`);

--
-- Index pour la table `reponses_utilisateurs`
--
ALTER TABLE `reponses_utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id_test`),
  ADD KEY `idx_tests_id_metier` (`id_metier`),
  ADD KEY `fk_tests_question` (`id_question`),
  ADD KEY `idx_test_token` (`test_token`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `account_verification_logs`
--
ALTER TABLE `account_verification_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `formateur`
--
ALTER TABLE `formateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `opportunitetravail`
--
ALTER TABLE `opportunitetravail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reponses_utilisateurs`
--
ALTER TABLE `reponses_utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `test`
--
ALTER TABLE `test`
  MODIFY `id_test` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `opportunitetravail`
--
ALTER TABLE `opportunitetravail`
  ADD CONSTRAINT `opportunitetravail_ibfk_1` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD CONSTRAINT `reponses_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `fk_tests_question` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
