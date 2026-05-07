-- Clients : formation d'inscription (idempotent)
SET @c = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'client' AND column_name = 'nom_formation'
);
SET @sql = IF(@c = 0, 'ALTER TABLE client ADD COLUMN nom_formation VARCHAR(150) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
