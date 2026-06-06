<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/UtilisateurFactory.php';
require_once __DIR__.'/../classes/BaseDeDonnees.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
try {
    $user = Session::user();
    if (!in_array($user['role'], ['doyen','vice_doyen'], true)) throw new RuntimeException('Droit insuffisant.');
    $obj = trim($_POST['objet'] ?? '');
    $date = trim($_POST['date_heure'] ?? '');
    if (!$date && !empty($_POST['date']) && !empty($_POST['heure'])) $date = $_POST['date'].' '.$_POST['heure'].':00';
    $lieu = trim($_POST['lieu'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    if (!$obj || !$date || !$lieu) throw new RuntimeException('Objet, date/heure et lieu obligatoires.');
    $stmt = BaseDeDonnees::connexion()->prepare('SELECT * FROM users WHERE id=?'); $stmt->execute([$user['id']]);
    $u = UtilisateurFactory::creer($stmt->fetch());
    $u->convoquer($obj, $date, $lieu, $msg);
    json_response(['ok'=>true,'message'=>'Convocation envoyée aux enseignants et assistants.']);
} catch(Throwable $e) { json_response(['ok'=>false,'message'=>$e->getMessage()], 400); }
