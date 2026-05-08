-- Idempotent migration: critere -> session, formation dates/duree -> nb_place

-- Rename critere to session if critere exists and session does not
SET @critere_exists = (
  SELECT COUNT(*) FROM information_schema.tables
  WHERE table_schema = DATABASE() AND table_name = 'critere'
);
SET @session_exists = (
  SELECT COUNT(*) FROM information_schema.tables
  WHERE table_schema = DATABASE() AND table_name = 'session'
);
SET @sql_rename = IF(@critere_exists > 0 AND @session_exists = 0, 'RENAME TABLE critere TO `session`', 'SELECT 1');
PREPARE stmt FROM @sql_rename;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add date columns to session if missing
SET @col_dd = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'session' AND column_name = 'date_debut'
);
SET @sql_dd = IF(@col_dd = 0,
  'ALTER TABLE `session` ADD COLUMN date_debut DATE NOT NULL DEFAULT ''1970-01-01'', ADD COLUMN date_fin DATE NOT NULL DEFAULT ''1970-01-02''',
  'SELECT 1');
PREPARE stmt2 FROM @sql_dd;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- Formation: drop old columns and add nb_place (idempotent per column)
SET @f_dd = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'date_debut'
);
SET @sql_fdd = IF(@f_dd > 0, 'ALTER TABLE formation DROP COLUMN date_debut', 'SELECT 1');
PREPARE stmt3 FROM @sql_fdd;
EXECUTE stmt3;
DEALLOCATE PREPARE stmt3;

SET @f_df = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'date_fin'
);
SET @sql_fdf = IF(@f_df > 0, 'ALTER TABLE formation DROP COLUMN date_fin', 'SELECT 1');
PREPARE stmt4 FROM @sql_fdf;
EXECUTE stmt4;
DEALLOCATE PREPARE stmt4;

SET @f_du = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'duree'
);
SET @sql_fdu = IF(@f_du > 0, 'ALTER TABLE formation DROP COLUMN duree', 'SELECT 1');
PREPARE stmt5 FROM @sql_fdu;
EXECUTE stmt5;
DEALLOCATE PREPARE stmt5;

SET @f_np = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'nb_place'
);
SET @sql_fnp = IF(@f_np = 0, 'ALTER TABLE formation ADD COLUMN nb_place INT NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE stmt6 FROM @sql_fnp;
EXECUTE stmt6;
DEALLOCATE PREPARE stmt6;
