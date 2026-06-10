<?php
require_once __DIR__.'/BaseDeDonnees.php';
require_once __DIR__.'/Session.php';
final class Auth {
    public function login(string $identifiant, string $password): bool {
        $sql='SELECT * FROM users WHERE identifiant=:identifiant LIMIT 1';
        $st=BaseDeDonnees::connexion()->prepare($sql); $st->execute(['identifiant'=>trim($identifiant)]); $user=$st->fetch();
        if($user && password_verify($password, $user['password_hash'])) { Session::connecter($user); return true; }
        return false;
    }
}
