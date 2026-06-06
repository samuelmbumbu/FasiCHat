<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Valve.php';
require_once __DIR__.'/../classes/GestionFichier.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
try {
    $user = Session::user(); $valve = new Valve();
    $action = $_POST['action'] ?? 'create';
    if ($action === 'delete') $valve->supprimer((int)$_POST['id'], $user['role']);
    elseif ($action === 'update') $valve->modifier((int)$_POST['id'], $user['role'], $_POST['titre'] ?? '', $_POST['contenu'] ?? '', $_POST['categorie'] ?? 'info');
    else {
        $fichier = null;
        if (!empty($_FILES['fichier']['tmp_name'])) $fichier = (new GestionFichier())->sauvegarder($_FILES['fichier']);
        $valve->publier($user['id'], $user['role'], $_POST['titre'] ?? '', $_POST['contenu'] ?? '', $_POST['categorie'] ?? 'info', $_POST['date_expiration'] ?? null, $fichier);
    }
    json_response(['ok'=>true,'message'=>'Action Valve effectuée.']);
} catch(Throwable $e) { json_response(['ok'=>false,'message'=>$e->getMessage()], 400); }
