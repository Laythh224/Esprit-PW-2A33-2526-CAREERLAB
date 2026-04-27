-- Career Lab (PHP MVC / PDO) schema
-- MySQL / MariaDB, utf8mb4

CREATE DATABASE IF NOT EXISTS career_lab
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE career_lab;

-- Posts
CREATE TABLE IF NOT EXISTS post (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  body TEXT NOT NULL,
  photo_url VARCHAR(2048) NULL,
  created_at DATE NOT NULL,
  status ENUM('active','locked','deleted') NOT NULL DEFAULT 'active',
  flair ENUM('Question','Thread','Disclaimer') NOT NULL,
  upvote_count INT UNSIGNED NOT NULL DEFAULT 0,
  author_type ENUM('Utilisateur','Formateur','Entreprise') NOT NULL,
  -- Minimum upvotes (on post) for this post to be eligible for challenge activation
  min_upvotes_for_challenge INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_post_status_created (status, created_at),
  KEY idx_post_upvotes (upvote_count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Challenges (belongs to a post)
CREATE TABLE IF NOT EXISTS challenge (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id BIGINT UNSIGNED NOT NULL,
  theme VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  flair ENUM('Projet','Showcast','Débat','Pitch') NOT NULL,
  creator_type ENUM('Formateur','Entreprise') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  -- Auto-managed: upcoming until attached post reaches min_upvotes_for_challenge
  status ENUM('upcoming','active','closed') NOT NULL DEFAULT 'upcoming',
  reward_type ENUM('job','course') NOT NULL,
  reward_title VARCHAR(200) NOT NULL,
  reward_description TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_challenge_status (status),
  KEY idx_challenge_post (post_id),
  CONSTRAINT fk_challenge_post
    FOREIGN KEY (post_id) REFERENCES post(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: comments for challenges (blocked when status != active at app level)
CREATE TABLE IF NOT EXISTS challenge_comment (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  challenge_id BIGINT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  image_url VARCHAR(2048) NULL,
  video_url VARCHAR(2048) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  upvote_count INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_comment_challenge (challenge_id, created_at),
  CONSTRAINT fk_comment_challenge
    FOREIGN KEY (challenge_id) REFERENCES challenge(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example JOIN query (Challenge + Post)
-- Shows post upvotes + min required + auto eligibility
-- (Run in phpMyAdmin)
-- SELECT
--   c.*,
--   p.title AS post_title,
--   p.upvote_count AS post_upvotes,
--   p.min_upvotes_for_challenge AS post_min_for_challenge,
--   (p.upvote_count >= p.min_upvotes_for_challenge) AS post_is_eligible
-- FROM challenge c
-- JOIN post p ON p.id = c.post_id
-- ORDER BY c.created_at DESC;

