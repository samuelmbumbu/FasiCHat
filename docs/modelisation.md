# Modélisation technique — FasiChat Classroom

## 1. Choix général
L'application sépare clairement le frontend fourni, les classes métier et les actions PHP. Le frontend garde son apparence originale, tandis que le backend ajoute la logique d'authentification, de messagerie, de convocation, de Valve et de gestion des fichiers.

## 2. Hiérarchie des utilisateurs
La classe abstraite `Utilisateur` contient les propriétés communes : identifiant, nom, rôle et méthode `tableauDeBord()`. Les rôles pédagogiques héritent de cette classe : `Etudiant`, `Enseignant`, `Assistant`. Les rôles administratifs héritent de `Administratif` : `Doyen`, `ViceDoyen`, `Apparitaire`.

Pour éviter la duplication, les droits communs de convocation du Doyen et du Vice-Doyen sont modélisés par l'interface `Convocable` et le `TraitConvocation`. Ainsi, les deux classes disposent de la même méthode `convoquer()` sans recopier le code.

## 3. Messages et polymorphisme
La classe abstraite `Message` définit la méthode commune `envoyer()`. Les classes `MessagePrive`, `MessagePublic` et `MurPedagogique` redéfinissent seulement le type du message. Cette structure respecte le polymorphisme demandé : chaque message s'enregistre de manière uniforme, mais garde une nature différente.

## 4. Convocation
La classe `Convocation` enregistre l'objet, la date, le lieu et le message. Les destinataires sont récupérés dynamiquement depuis la table `users` avec les rôles `enseignant` et `assistant`. Les étudiants ne reçoivent donc pas de convocation.

## 5. Valve
La classe `Valve` centralise les opérations publier, modifier, supprimer et lister. Avant toute écriture, elle vérifie explicitement que le rôle est `apparitaire`. Les autres rôles peuvent seulement consulter.

## 6. Sécurité
Le backend utilise PDO avec requêtes préparées, `password_hash()` / `password_verify()`, sessions avec cookie `httponly`, filtrage simple des champs et échappement HTML via la fonction `e()`. L'upload limite les fichiers à 20 Mo et contrôle le type MIME.

## 7. Base de données
Le schéma relationnel contient les tables : `users`, `promotions`, `cours`, `cours_users`, `messages`, `convocations`, `convocation_destinataires` et `annonces_valve`. Les clés étrangères permettent de conserver la cohérence entre utilisateurs, cours, messages et annonces.
