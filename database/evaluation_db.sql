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
