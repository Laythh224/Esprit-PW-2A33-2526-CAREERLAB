-- Lie chaque client a la session d'inscription (pour rendre une place si suppression).
SET @c = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'client' AND column_name = 'session_id'
);
SET @sql = IF(@c = 0, 'ALTER TABLE client ADD COLUMN session_id INT DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
