<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Message.php';
Session::demarrer(); Session::requireLogin();
$user = Session::user();
$contenu = trim($_POST['contenu'] ?? '');
$type = $_POST['type'] ?? 'prive';
if ($contenu === '') exit('Message vide.');
$message = match($type) {
    'public' => new MessagePublic($user['id'], $contenu, null, (int)($_POST['cours_id'] ?? 1)),
    'mur' => new MurPedagogique($user['id'], $contenu, null, (int)($_POST['cours_id'] ?? 1)),
    default => new MessagePrive($user['id'], $contenu, (int)($_POST['receiver_id'] ?? 0))
};
$message->envoyer();
header('Location: ../../dashboard_etudiant.html');
