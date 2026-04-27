-- Career Lab — schéma MySQL (MariaDB / MySQL 5.7+)
-- À exécuter dans phpMyAdmin : onglet SQL, ou : mysql -u root -p < career_lab_schema.sql
--
-- Ce schéma reflète les données actuelles côté navigateur (localStorage) :
--   blogPosts, blogChallenges, challengeComments
--
-- Encodage : utf8mb4 pour tous les textes (dont emojis si besoin).

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS career_lab
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE career_lab;

-- ---------------------------------------------------------------------------
-- Articles (blog)
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS post_upvotes;
DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title           VARCHAR(512)    NOT NULL,
  body            MEDIUMTEXT      NOT NULL,
  photo_url       VARCHAR(2048)   NULL DEFAULT NULL,
  created_at      DATE            NOT NULL,
  status          ENUM('active','locked','deleted') NOT NULL DEFAULT 'active',
  flair           ENUM('Question','Thread','Disclaimer') NOT NULL,
  author_type     ENUM('Utilisateur','Formateur','Entreprise') NOT NULL,
  upvote_count    INT UNSIGNED    NOT NULL DEFAULT 0,
  -- Sondage : même longueur que poll_options (2 à 5 options)
  poll_options    JSON            NULL COMMENT '["opt1","opt2",...]',
  poll_votes      JSON            NULL COMMENT '[n1,n2,...] aligné sur poll_options',
  created_at_ts   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at_ts   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_posts_status (status),
  KEY idx_posts_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Un visiteur ne vote qu’une fois par article (équivalent upvotedBy[] en JS)
CREATE TABLE post_upvotes (
  post_id     BIGINT UNSIGNED NOT NULL,
  visitor_id  VARCHAR(64)     NOT NULL COMMENT 'ex. careerLabVisitorId',
  voted_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (post_id, visitor_id),
  CONSTRAINT fk_post_upvotes_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Défis
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS challenge_comment_upvotes;
DROP TABLE IF EXISTS challenge_comments;
DROP TABLE IF EXISTS challenge_upvotes;
DROP TABLE IF EXISTS challenges;

CREATE TABLE challenges (
  id                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  theme                VARCHAR(512)    NOT NULL,
  description          MEDIUMTEXT      NOT NULL,
  flair                ENUM('Projet','Showcast','Débat','Pitch') NOT NULL,
  creator_type         VARCHAR(64)     NOT NULL,
  start_date           DATE            NOT NULL,
  end_date             DATE            NOT NULL,
  status               ENUM('upcoming','active','closed') NOT NULL DEFAULT 'active',
  min_upvotes_to_enter INT UNSIGNED    NOT NULL DEFAULT 1,
  reward_type          VARCHAR(32)     NOT NULL,
  reward_title         VARCHAR(512)    NOT NULL,
  reward_description   MEDIUMTEXT      NOT NULL,
  winner_id            VARCHAR(64)     NULL DEFAULT NULL,
  upvote_count         INT UNSIGNED    NOT NULL DEFAULT 0,
  created_at_ts        TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at_ts        TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_challenges_status (status),
  KEY idx_challenges_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE challenge_upvotes (
  challenge_id BIGINT UNSIGNED NOT NULL,
  visitor_id   VARCHAR(64)     NOT NULL,
  voted_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (challenge_id, visitor_id),
  CONSTRAINT fk_ch_up_ch FOREIGN KEY (challenge_id) REFERENCES challenges (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Commentaires sur les défis (+ médias optionnels)
-- ---------------------------------------------------------------------------
CREATE TABLE challenge_comments (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  challenge_id  BIGINT UNSIGNED NOT NULL,
  body          MEDIUMTEXT      NOT NULL,
  image_url     VARCHAR(2048)   NULL DEFAULT NULL,
  video_url     VARCHAR(2048)   NULL DEFAULT NULL,
  upvote_count  INT UNSIGNED    NOT NULL DEFAULT 0,
  created_at    DATE            NOT NULL,
  created_at_ts TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cc_challenge (challenge_id),
  CONSTRAINT fk_cc_challenge FOREIGN KEY (challenge_id) REFERENCES challenges (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE challenge_comment_upvotes (
  comment_id  BIGINT UNSIGNED NOT NULL,
  visitor_id  VARCHAR(64)     NOT NULL,
  voted_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (comment_id, visitor_id),
  CONSTRAINT fk_ccu_comment FOREIGN KEY (comment_id) REFERENCES challenge_comments (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
