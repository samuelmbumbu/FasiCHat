CREATE DATABASE IF NOT EXISTS fasichat_classroom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fasichat_classroom;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS convocation_destinataires;
DROP TABLE IF EXISTS convocations;
DROP TABLE IF EXISTS annonces_valve;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS cours_users;
DROP TABLE IF EXISTS cours;
DROP TABLE IF EXISTS promotions;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

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
  contenu TEXT NULL,
  fichier_url VARCHAR(255) NULL,
  fichier_nom VARCHAR(255) NULL,
  fichier_type VARCHAR(40) NULL,
  fichier_taille INT NULL,
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
  categorie ENUM('urgence','convocation','info','academique') DEFAULT 'info',
  date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_expiration DATE NULL,
  fichier_url VARCHAR(255) NULL,
  fichier_nom VARCHAR(255) NULL,
  FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO promotions(nom) VALUES ('L2 Sciences Informatiques'), ('L3 Sciences Informatiques');
INSERT INTO cours(nom, promotion_id) VALUES ('Programmation Web PHP POO', 1), ('Base de données', 1), ('Génie logiciel', 2);

INSERT INTO users(nom, identifiant, password_hash, role, promotion_id) VALUES
('Pr. KUTANGILA', 'doyen@faculte.edu', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'doyen', NULL),
('Pr. MANPUYA', 'vdoyen@faculte.edu', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'vice_doyen', NULL),
('DJ. ROLLY', 'apparitaire@faculte.edu', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'apparitaire', NULL),
('Prof. KABEYA', 'enseignant@faculte.edu', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'enseignant', NULL),
('Ass. MUKENDI', 'assistant@faculte.edu', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'assistant', NULL),
('JORDY BIAYA', 'SI2024001', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'etudiant', 1),
('MIRIAM YULILA', 'SI2024002', '$2y$12$8XwURJbqPMZ9KckVhqrdM.vNCB/kCvRbuOBDfNzhPSZlihdPmbZku', 'etudiant', 1);

INSERT INTO cours_users(cours_id, user_id) VALUES (1,4),(1,5),(1,6),(1,7),(2,4),(2,6),(2,7);

INSERT INTO messages(sender_id, receiver_id, cours_id, type_message, contenu, fichier_nom, fichier_type, fichier_taille) VALUES
(6,7,NULL,'prive','Salut Miriam, tu as compris le TP ?',NULL,NULL,NULL),
(4,NULL,1,'mur_pedagogique','N’oubliez pas de respecter la POO native et PDO.',NULL,NULL,NULL),
(6,NULL,1,'public','Professeur, devons-nous compresser les vidéos aussi ?',NULL,NULL,NULL),
(4,NULL,1,'public','Voici le sujet du projet PHP POO.','Sujet_Projet_PHP_POO.pdf','document',2400000);

INSERT INTO convocations(sender_id, objet, date_heure, lieu, message) VALUES
(1,'Conseil pédagogique PHP POO','2026-07-20 10:00:00','Salle A-12','Réunion préparatoire pour la soutenance des projets.');
INSERT INTO convocation_destinataires(convocation_id, user_id) VALUES (1,4),(1,5);

INSERT INTO annonces_valve(auteur_id, titre, contenu, categorie, date_expiration) VALUES
(3,'Modification calendrier des examens S5','Les épreuves de Programmation Web sont reportées au lundi 27 juillet 2026.','urgence','2026-07-27'),
(3,'Convocation des enseignants','Tous les enseignants sont invités à la réunion pédagogique du jeudi.','convocation','2026-08-01'),
(3,'Dépôt des projets PHP','Les projets doivent être déposés sur GitHub avec le fichier SQL de démonstration.','info','2026-07-30'),
(3,'Rappel académique','Le rapport technique doit expliquer la hiérarchie des classes et l’usage de PDO.','academique',NULL);
