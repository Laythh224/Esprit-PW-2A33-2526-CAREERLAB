-- Idempotent: table client (CIN PK 8 chiffres)

CREATE TABLE IF NOT EXISTS client (
  cin VARCHAR(8) NOT NULL,
  nom VARCHAR(80) NOT NULL,
  prenom VARCHAR(80) NOT NULL,
  adresse VARCHAR(200) NOT NULL,
  niveau VARCHAR(80) NOT NULL,
  age TINYINT UNSIGNED NOT NULL,
  tel VARCHAR(8) NOT NULL,
  PRIMARY KEY (cin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
