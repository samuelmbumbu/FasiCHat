# 🎓 FasiChat Classroom

Application web académique développée en PHP orienté objet et MySQL permettant la gestion des échanges pédagogiques au sein d'une faculté.

---

## 📌 Présentation

FasiChat Classroom est une plateforme de communication universitaire inspirée des environnements collaboratifs modernes.

L'application permet aux étudiants, enseignants, assistants, doyens, vice-doyens et apparitaires d'échanger des informations, de communiquer dans les cours, de consulter les valves académiques et de gérer les activités pédagogiques.

---

## 👥 Rôles disponibles

### 🎓 Étudiant
- Consulter les cours auxquels il est inscrit
- Participer aux discussions publiques des cours
- Envoyer des messages privés
- Consulter la valve académique
- Télécharger les documents partagés

### 👨‍🏫 Enseignant
- Gérer ses cours
- Publier des messages pédagogiques
- Communiquer avec les étudiants
- Partager des fichiers de cours
- Consulter les convocations

### 👨‍💼 Assistant
- Assister l'enseignant dans la gestion des cours
- Participer aux échanges pédagogiques
- Partager des documents

### 🏛️ Doyen
- Consulter l'ensemble des activités académiques
- Émettre des convocations
- Publier des annonces institutionnelles

### 🏢 Vice-Doyen
- Assister le doyen dans la gestion académique
- Consulter les informations pédagogiques

### 📋 Apparitaire
- Gérer les valves
- Diffuser les communications administratives
- Publier les annonces officielles

---

## 🚀 Fonctionnalités principales

### 💬 Messagerie
- Messages publics par cours
- Messages privés
- Historique des conversations
- Affichage en temps réel des échanges

### 📚 Gestion des cours
- Attribution des enseignants
- Inscription des étudiants
- Consultation des cours
- Discussions pédagogiques

### 📎 Partage de fichiers
- Documents PDF
- Images
- Vidéos
- Messages vocaux

### 📢 Valve académique
- Publication des annonces
- Consultation des communiqués
- Gestion des informations administratives

### 🏛️ Convocations
- Création des convocations
- Attribution des destinataires
- Consultation des convocations reçues

### 🔐 Sécurité
- Authentification par session PHP
- Gestion des rôles utilisateurs
- Contrôle d'accès selon les privilèges

---

## 🛠️ Technologies utilisées

### Backend
- PHP 8+
- Programmation Orientée Objet (POO)
- Architecture MVC simplifiée
- Sessions PHP

### Base de données
- MySQL / MariaDB
- phpMyAdmin

### Frontend
- HTML5
- CSS3
- JavaScript Vanilla

---

## 📂 Structure du projet

```text
fasiclass/
│
├── app/
│   ├── classes/
│   ├── controllers/
│   ├── views/
│   └── config.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── uploads/
│
├── database/
│   └── fasichat_demo.sql
│
├── dashboard.php
├── chat.php
├── cours.php
├── valve.php
├── login.php
└── logout.php
```

---

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/samuelmbumbu/FasiChat.git
```

### 2. Copier le projet dans MAMP

```text
/Applications/MAMP/htdocs/fasiclass
```

### 3. Démarrer MAMP

- Apache : ON
- MySQL : ON

### 4. Créer la base de données

Dans phpMyAdmin :

```sql
CREATE DATABASE fasichat_classroom;
```

Puis importer :

```text
database/fasichat_demo.sql
```

### 5. Vérifier la configuration

Fichier :

```php
app/config.php
```

Configurer :

```php
DB_HOST
DB_NAME
DB_USER
DB_PASS
```

### 6. Lancer l'application

```text
http://localhost:8888/fasiclass/login.php
```

---

## 🔑 Comptes de démonstration

Mot de passe pour tous :

```text
password
```

| Rôle | Identifiant |
|--------|------------|
| Étudiant | SI2024001 |
| Enseignant | enseignant@faculte.edu |
| Assistant | assistant@faculte.edu |
| Doyen | doyen@faculte.edu |
| Vice-Doyen | vdoyen@faculte.edu |
| Apparitaire | apparitaire@faculte.edu |

---

## 📖 Concepts POO utilisés

- Encapsulation
- Héritage
- Polymorphisme
- Classes abstraites
- Interfaces
- Factory Pattern
- Gestion des rôles utilisateurs

---

## 🎯 Objectif pédagogique

Ce projet a été réalisé dans le cadre du cours de Programmation Orientée Objet afin de mettre en pratique :

- PHP Orienté Objet
- MySQL
- Gestion des utilisateurs
- Architecture logicielle
- Communication temps réel simulée
- Gestion documentaire académique

---

## 👨‍💻 Auteur

**Samuel Mbumbu**

Étudiant en Informatique

Projet académique — FasiChat Classroom
