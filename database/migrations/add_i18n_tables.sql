-- Add translation tables for questions and reponses
CREATE TABLE IF NOT EXISTS question_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_question INT NOT NULL,
    lang VARCHAR(5) NOT NULL,
    texte TEXT NOT NULL,
    CONSTRAINT uq_question_lang UNIQUE (id_question, lang),
    CONSTRAINT fk_qt_question FOREIGN KEY (id_question) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reponse_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reponse INT NOT NULL,
    lang VARCHAR(5) NOT NULL,
    texte TEXT NOT NULL,
    CONSTRAINT uq_reponse_lang UNIQUE (id_reponse, lang),
    CONSTRAINT fk_rt_reponse FOREIGN KEY (id_reponse) REFERENCES reponses(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example inserts (uncomment and adapt as needed):
-- INSERT INTO question_translations (id_question, lang, texte) VALUES (1,'en','What is your favorite job?');
-- INSERT INTO reponse_translations (id_reponse, lang, texte) VALUES (1,'en','Doctor');
