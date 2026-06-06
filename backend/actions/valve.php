<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Valve.php';
Session::demarrer(); Session::requireLogin();
$user = Session::user(); $valve = new Valve();
$action = $_POST['action'] ?? 'create';
if ($action === 'delete') $valve->supprimer((int)$_POST['id'], $user['role']);
elseif ($action === 'update') $valve->modifier((int)$_POST['id'], $user['role'], $_POST['titre'] ?? '', $_POST['contenu'] ?? '');
else $valve->publier($user['id'], $user['role'], $_POST['titre'] ?? '', $_POST['contenu'] ?? '', $_POST['date_expiration'] ?? null);
header('Location: ../../valve.html?valve=ok');
