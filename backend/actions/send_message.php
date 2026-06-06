<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Message.php';
require_once __DIR__.'/../classes/GestionFichier.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
try {
    $user = Session::user();
    $contenu = trim($_POST['contenu'] ?? $_POST['message'] ?? '');
    $type = $_POST['type'] ?? 'public';
    $coursId = (int)($_POST['cours_id'] ?? 1);
    $receiverId = (int)($_POST['receiver_id'] ?? 0);
    $fichier = null;
    if (!empty($_FILES['fichier']['tmp_name'])) $fichier = (new GestionFichier())->sauvegarder($_FILES['fichier']);
    if ($contenu === '' && !$fichier) throw new RuntimeException('Message vide.');
    $message = match($type) {
        'prive' => new MessagePrive($user['id'], $contenu, $receiverId ?: null, null, $fichier),
        'mur', 'mur_pedagogique' => new MurPedagogique($user['id'], $contenu, null, $coursId, $fichier),
        default => new MessagePublic($user['id'], $contenu, null, $coursId, $fichier),
    };
    $message->envoyer();
    json_response(['ok'=>true,'message'=>'Message envoyé.','fichier'=>$fichier]);
} catch (Throwable $e) { json_response(['ok'=>false,'message'=>$e->getMessage()], 400); }
