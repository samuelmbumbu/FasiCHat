# FasiChat Classroom — Version finale corrigée

Projet PHP POO natif pour le TP **FasiChat Classroom**.

## Ce qui a été corrigé/complété

- Authentification avec sessions PHP sécurisées.
- Redirection selon le rôle connecté.
- Backend PHP orienté objet : utilisateurs, messages, convocations, valve, fichiers.
- Envoi réel de messages texte vers MySQL.
- Upload réel de fichiers : PDF, Word, images, vidéos et audio, avec limite de 20 Mo.
- Enregistrement audio via le navigateur quand le navigateur autorise le micro.
- Publication réelle d'annonces Valve par l'Apparitaire.
- Filtrage dynamique du Valve : urgences, convocations, infos, académique.
- Envoi réel de convocations par le Doyen et le Vice-Doyen aux enseignants/assistants.
- Accès MySQL exclusivement via PDO et requêtes préparées.

## Installation avec MAMP

1. Copier le dossier dans `htdocs`, par exemple :

```bash
/Applications/MAMP/htdocs/fasiclass
```

2. Démarrer MAMP.

3. Ouvrir phpMyAdmin.

4. Importer le fichier :

```text
database/fasichat_demo.sql
```

5. Vérifier `backend/config.php` :

```php
const DB_HOST = '127.0.0.1;port=8889';
const DB_NAME = 'fasichat_classroom';
const DB_USER = 'root';
const DB_PASS = 'root';
```

6. Ouvrir :

```text
http://localhost:8888/fasiclass/login.html
```

## Comptes de test

Mot de passe pour tous les comptes :

```text
password
```

| Rôle | Identifiant |
|---|---|
| Étudiant | SI2024001 |
| Étudiant | SI2024002 |
| Enseignant | enseignant@faculte.edu |
| Assistant | assistant@faculte.edu |
| Doyen | doyen@faculte.edu |
| Vice-Doyen | vdoyen@faculte.edu |
| Apparitaire | apparitaire@faculte.edu |

## Important

- Pour publier sur le Valve, connecte-toi comme Apparitaire.
- Pour convoquer une réunion, connecte-toi comme Doyen ou Vice-Doyen.
- Pour envoyer audio/fichiers, le navigateur doit autoriser le micro ou l'accès aux fichiers.
- Si tu utilises XAMPP, modifie dans `backend/config.php` :

```php
const DB_HOST = '127.0.0.1';
const DB_PASS = '';
```

## Technologies

- PHP 8 natif
- MySQL
- PDO
- HTML/CSS/JavaScript
- POO PHP : classes abstraites, héritage, interface, trait, encapsulation
