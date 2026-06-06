<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/UtilisateurFactory.php';

Session::demarrer();

$identifiant = trim($_POST['identifiant'] ?? '');
$password = $_POST['password'] ?? '';
$roleChoisi = $_POST['role_choisi'] ?? '';

$auth = new Auth();
$user = $auth->login($identifiant, $password);

if (!$user) {
    header('Location: ../../login.html?erreur=1');
    exit;
}

if (!empty($roleChoisi) && $user['role'] !== $roleChoisi) {
    header('Location: ../../login.html?erreur=role');
    exit;
}

$objet = UtilisateurFactory::creer($user);

$dashboard = $objet->tableauDeBord();

header('Location: ../../' . $dashboard);
exit;
