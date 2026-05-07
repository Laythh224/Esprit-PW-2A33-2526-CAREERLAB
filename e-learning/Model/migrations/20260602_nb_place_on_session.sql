-- Deplace nb_place de formation vers session (idempotent, MySQL 5.7+)
-- 1) Colonne sur session
SET @snp = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'session' AND column_name = 'nb_place'
);
SET @sql1 = IF(@snp = 0, 'ALTER TABLE `session` ADD COLUMN nb_place INT NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE stmt1 FROM @sql1;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;

-- 2) Recopie depuis formation si la colonne existe encore
SET @fnp = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'nb_place'
);
SET @sql2 = IF(
  @fnp > 0,
  'UPDATE `session` s INNER JOIN formation f ON s.nom_formation = f.nom_formation SET s.nb_place = f.nb_place',
  'SELECT 1'
);
PREPARE stmt2 FROM @sql2;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- 3) Suppression sur formation
SET @sql3 = IF(
  @fnp > 0,
  'ALTER TABLE formation DROP COLUMN nb_place',
  'SELECT 1'
);
PREPARE stmt3 FROM @sql3;
EXECUTE stmt3;
DEALLOCATE PREPARE stmt3;
