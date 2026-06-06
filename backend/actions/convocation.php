<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/UtilisateurFactory.php';
require_once __DIR__.'/../classes/BaseDeDonnees.php';
Session::demarrer(); Session::requireLogin();
$user = Session::user();
if (!in_array($user['role'], ['doyen','vice_doyen'], true)) exit('Droit insuffisant.');
$obj = trim($_POST['objet'] ?? ''); $date = trim($_POST['date_heure'] ?? ''); $lieu = trim($_POST['lieu'] ?? ''); $msg = trim($_POST['message'] ?? '');
$stmt = BaseDeDonnees::connexion()->prepare('SELECT * FROM users WHERE id=?'); $stmt->execute([$user['id']]);
$u = UtilisateurFactory::creer($stmt->fetch());
$u->convoquer($obj, $date, $lieu, $msg);
header('Location: ../../dashboard_admin.html?convocation=ok');
