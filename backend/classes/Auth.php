<?php
require_once __DIR__.'/BaseDeDonnees.php';
require_once __DIR__.'/Session.php';

class Auth {
    public function login(string $identifiant, string $password): ?array {
        $sql = 'SELECT * FROM users WHERE identifiant = :identifiant LIMIT 1';
        $stmt = BaseDeDonnees::connexion()->prepare($sql);
        $stmt->execute(['identifiant'=>$identifiant]);
        $user = $stmt->fetch();

        if ($user && password_verify(trim($password), trim($user['password_hash']))) {
            Session::connecter($user);
            return $user;
        }

        return null;
    }
}
