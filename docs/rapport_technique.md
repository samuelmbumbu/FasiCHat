# Rapport technique — FasiChat Classroom

## Choix de conception
L'application est développée en PHP natif orienté objet. La classe abstraite `Utilisateur` définit les attributs communs. Les classes `Etudiant`, `Enseignant`, `Assistant`, `Doyen`, `ViceDoyen` et `Apparitaire` spécialisent les comportements.

## Hiérarchie administrative
`Doyen` et `ViceDoyen` implémentent l'interface `Convocable` et réutilisent le `TraitConvocation`, ce qui évite la duplication de code. `Apparitaire` n'a pas ce trait et ne peut gérer que la Valve.

## Messagerie
Les classes `MessagePrive`, `MessagePublic` et `MurPedagogique` héritent de `Message` et redéfinissent le type logique du message. La visibilité est gérée par les requêtes SQL selon le type de conversation.

## Fichiers
`GestionFichier` contrôle le type MIME, la taille maximale de 20 Mo, sauvegarde les fichiers dans `uploads/` et compresse les images JPEG/PNG si l'extension GD est disponible.

## Sécurité
Les accès sensibles sont protégés par session et contrôle de rôle. Les sorties sont échappées avec `htmlspecialchars`. Les échanges avec MySQL passent par PDO et des requêtes préparées.
