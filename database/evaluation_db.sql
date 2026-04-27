CREATE DATABASE IF NOT EXISTS evaluation_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE evaluation_db;

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texte TEXT NOT NULL,
    id_metier INT NOT NULL
);

CREATE TABLE IF NOT EXISTS reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texte TEXT NOT NULL,
    est_correcte BOOLEAN NOT NULL,
    id_question INT NOT NULL,
    KEY idx_reponses_id_question (id_question),
    CONSTRAINT fk_reponses_questions
        FOREIGN KEY (id_question)
        REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS reponses_utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_question INT NOT NULL,
    id_reponse INT NOT NULL,
    attempt_token VARCHAR(64) NOT NULL DEFAULT '',
    est_correcte BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_rep_user_question (id_question),
    KEY idx_rep_user_reponse (id_reponse),
    KEY idx_rep_user_correct (est_correcte),
    KEY idx_rep_user_attempt (attempt_token),
    CONSTRAINT fk_rep_user_question
        FOREIGN KEY (id_question)
        REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_rep_user_reponse
        FOREIGN KEY (id_reponse)
        REFERENCES reponses(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
