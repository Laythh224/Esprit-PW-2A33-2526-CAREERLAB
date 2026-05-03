-- Idempotent: ajoute nb_place a formation si la colonne est absente (phpMyAdmin / CLI)

SET @exists = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'nb_place'
);
SET @sql = IF(@exists = 0,
  'ALTER TABLE formation ADD COLUMN nb_place INT NOT NULL DEFAULT 1',
  'SELECT 1 AS skip_nb_place');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
