<?php
function h(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function db(): PDO { return BaseDeDonnees::connexion(); }
function current_user(): array { return Session::requireLogin(); }
function role_label(string $r): string { return ['etudiant'=>'Étudiant','enseignant'=>'Enseignant','assistant'=>'Assistant','doyen'=>'Doyen','vice_doyen'=>'Vice-Doyen','apparitaire'=>'Apparitaire'][$r] ?? $r; }
