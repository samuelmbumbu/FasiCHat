<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/BaseDeDonnees.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
$user=Session::user();
$pdo=BaseDeDonnees::connexion();
if (in_array($user['role'], ['enseignant','assistant'], true)) {
 $stmt=$pdo->prepare('SELECT c.*, u.nom auteur FROM convocations c JOIN convocation_destinataires d ON d.convocation_id=c.id JOIN users u ON u.id=c.sender_id WHERE d.user_id=? ORDER BY c.created_at DESC');
 $stmt->execute([$user['id']]);
} else {
 $stmt=$pdo->prepare('SELECT c.*, u.nom auteur FROM convocations c JOIN users u ON u.id=c.sender_id ORDER BY c.created_at DESC'); $stmt->execute();
}
json_response(['ok'=>true,'convocations'=>$stmt->fetchAll()]);
