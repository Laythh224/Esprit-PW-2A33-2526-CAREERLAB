CREATE TABLE IF NOT EXISTS test (
    id_test INT AUTO_INCREMENT PRIMARY KEY,
    test_token VARCHAR(64) NOT NULL,
    user_name VARCHAR(255) DEFAULT NULL,
    user_email VARCHAR(255) DEFAULT NULL,
    date DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    id_metier INT NOT NULL,
    id_question INT NOT NULL,
    KEY idx_test_token (test_token),
    KEY idx_tests_id_metier (id_metier),
    CONSTRAINT fk_tests_question
        FOREIGN KEY (id_question)
        REFERENCES questions(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- If the table already exists, apply the missing grouping column:
-- ALTER TABLE test ADD COLUMN test_token VARCHAR(64) NOT NULL AFTER id_test;
-- ALTER TABLE test ADD KEY idx_test_token (test_token);
