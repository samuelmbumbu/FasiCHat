# FasiChat Classroom — Backend PHP POO

Projet de messagerie académique en PHP orienté objet, avec le frontend fourni conservé et relié à un backend PHP natif.

## Installation locale

1. Copier le dossier dans `htdocs` ou `www`.
2. Créer/importer la base avec `database/fasichat_demo.sql` dans phpMyAdmin.
3. Vérifier les accès MySQL dans `backend/config.php`.
4. Ouvrir `login.html` dans le navigateur.

## Comptes de test

Mot de passe pour tous les comptes : `123456`

- Doyen : `doyen@faculte.edu`
- Vice-Doyen : `vdoyen@faculte.edu`
- Apparitaire : `apparitaire@faculte.edu`
- Enseignant : `enseignant@faculte.edu`
- Assistant : `assistant@faculte.edu`
- Étudiant : `SI2024001`

## Architecture

- `backend/classes` : classes POO obligatoires.
- `backend/actions` : scripts contrôleurs appelés par les formulaires.
- `database/fasichat_demo.sql` : schéma relationnel + données de test.
- `docs/modelisation.md` : justification de conception.

## Points respectés

- PHP natif, sans framework.
- POO avec classe abstraite, héritage, interface, trait et polymorphisme.
- PDO + requêtes préparées.
- Sessions PHP sécurisées.
- Contrôle des rôles pour Convocation et Valve.
- Upload limité à 20 Mo avec compression image via GD si disponible.
