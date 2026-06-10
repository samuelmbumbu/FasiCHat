# FasiChat Classroom — PHP POO

Application web académique de messagerie développée en PHP orienté objet, MySQL et PDO.

## Installation MAMP

1. Copier le dossier dans `/Applications/MAMP/htdocs/fasiclass`
2. Créer/importer la base avec `database/fasichat_demo.sql`
3. Vérifier `app/config.php`
4. Ouvrir `http://localhost:8888/fasiclass/login.php`

## Comptes de test

Mot de passe pour tous : `password`

- Étudiant : `SI2024001`
- Enseignant : `enseignant@faculte.edu`
- Assistant : `assistant@faculte.edu`
- Doyen : `doyen@faculte.edu`
- Vice-Doyen : `vdoyen@faculte.edu`
- Apparitaire : `apparitaire@faculte.edu`

## Fonctionnalités

- Authentification avec sessions sécurisées
- Rôles : Étudiant, Enseignant, Assistant, Doyen, Vice-Doyen, Apparitaire
- Messages privés entre étudiants et enseignants
- Messages publics par cours/promotion
- Mur pédagogique par cours
- Convocations du Doyen/Vice-Doyen aux enseignants et assistants
- Valve consultable par tous, modifiable uniquement par Apparitaire
- Upload PDF, documents, images, vidéos, audio jusqu’à 20 Mo
- PDO et requêtes préparées
- Architecture POO : classe abstraite, héritage, polymorphisme, interface, trait
