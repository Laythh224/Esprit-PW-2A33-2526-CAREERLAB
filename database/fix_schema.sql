-- Fix for Career Lab Challenge Schema Mismatch
-- This fixes the disconnect between PHP code and database structure

-- Drop incorrect tables (plural, no post_id relationship)
DROP TABLE IF EXISTS challenge_comment_upvotes;
DROP TABLE IF EXISTS challenge_comments;
DROP TABLE IF EXISTS challenge_upvotes;
DROP TABLE IF EXISTS challenges;

-- Drop any old post_upvotes  
DROP TABLE IF EXISTS post_upvotes;

-- Create correct schema with post_id relationship

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
  min_upvotes_for_challenge INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_post_status_created (status, created_at),
  KEY idx_post_upvotes (upvote_count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Challenges (with post_id relationship)
CREATE TABLE IF NOT EXISTS challenge (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id BIGINT UNSIGNED NOT NULL,
  theme VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  flair ENUM('Projet','Showcast','Débat','Pitch') NOT NULL,
  creator_type ENUM('Formateur','Entreprise') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
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

-- Challenge comments
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
