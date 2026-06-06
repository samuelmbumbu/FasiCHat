<?php
require_once __DIR__.'/../classes/Auth.php';
require_once __DIR__.'/../classes/UtilisateurFactory.php';
Session::demarrer();
$identifiant = trim($_POST['identifiant'] ?? $_GET['identifiant'] ?? '');
$password = $_POST['password'] ?? $_GET['password'] ?? '';
$auth = new Auth();
$user = $auth->login($identifiant, $password);
if (!$user) { header('Location: ../../login.html?erreur=1'); exit; }
$objet = UtilisateurFactory::creer($user);
header('Location: ../../' . $objet->tableauDeBord());
exit;
