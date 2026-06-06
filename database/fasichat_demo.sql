CREATE DATABASE IF NOT EXISTS fasichat_classroom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fasichat_classroom;

DROP TABLE IF EXISTS convocation_destinataires, convocations, annonces_valve, messages, cours_users, cours, promotions, users;

CREATE TABLE promotions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE cours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL,
  promotion_id INT NULL,
  FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL,
  identifiant VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('etudiant','enseignant','assistant','doyen','vice_doyen','apparitaire') NOT NULL,
  promotion_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE cours_users (
  cours_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY(cours_id, user_id),
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NULL,
  cours_id INT NULL,
  type_message ENUM('prive','public','mur_pedagogique') NOT NULL,
  contenu TEXT NOT NULL,
  fichier_url VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE convocations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  objet VARCHAR(180) NOT NULL,
  date_heure DATETIME NOT NULL,
  lieu VARCHAR(180) NOT NULL,
  message TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE convocation_destinataires (
  convocation_id INT NOT NULL,
  user_id INT NOT NULL,
  lu TINYINT(1) DEFAULT 0,
  PRIMARY KEY(convocation_id, user_id),
  FOREIGN KEY (convocation_id) REFERENCES convocations(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE annonces_valve (
  id INT AUTO_INCREMENT PRIMARY KEY,
  auteur_id INT NOT NULL,
  titre VARCHAR(180) NOT NULL,
  contenu TEXT NOT NULL,
  date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_expiration DATE NULL,
  FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO promotions(nom) VALUES ('L2 Sciences Informatiques'), ('L3 Sciences Informatiques');
INSERT INTO cours(nom, promotion_id) VALUES ('Programmation Web PHP POO', 1), ('Base de données', 1), ('Génie logiciel', 2);

INSERT INTO users(nom, identifiant, password_hash, role, promotion_id) VALUES
('Pr. KUTANGILA', 'doyen@faculte.edu', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'doyen', NULL),
('Pr. MANPUYA', 'vdoyen@faculte.edu', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'vice_doyen', NULL),
('DJ. ROLLY', 'apparitaire@faculte.edu', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'apparitaire', NULL),
('Prof. KABEYA', 'enseignant@faculte.edu', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'enseignant', NULL),
('Ass. MUKENDI', 'assistant@faculte.edu', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'assistant', NULL),
('JORDY BIAYA', 'SI2024001', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'etudiant', 1),
('MIRIAM YULILA', 'SI2024002', '$2y$12$/TNPmVavE1uJM.FoSnzqnuauapyWUxhFHM2sVtL1WKQEIjW0xbw7a', 'etudiant', 1);

INSERT INTO cours_users(cours_id, user_id) VALUES (1,4),(1,5),(1,6),(1,7),(2,4),(2,6),(2,7);
INSERT INTO messages(sender_id, receiver_id, cours_id, type_message, contenu) VALUES
(6,7,NULL,'prive','Salut Miriam, tu as compris le TP ?'),
(4,NULL,1,'mur_pedagogique','N’oubliez pas de respecter la POO native et PDO.'),
(6,NULL,1,'public','Professeur, devons-nous compresser les vidéos aussi ?');
INSERT INTO annonces_valve(auteur_id, titre, contenu, date_expiration) VALUES
(3,'Dépôt des projets PHP','Les projets doivent être déposés sur GitHub avec le fichier SQL de démonstration.','2026-07-30');
