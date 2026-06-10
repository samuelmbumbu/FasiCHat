<?php
require_once __DIR__.'/app/classes/Session.php'; require_once __DIR__.'/app/classes/UtilisateurFactory.php'; require_once __DIR__.'/app/helpers.php';
$u=Session::requireRole(['doyen','vice_doyen']); $objet=UtilisateurFactory::creer($u); $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){ $objet->convoquer($_POST['objet'], $_POST['date_heure'], $_POST['lieu'], $_POST['message'] ?? null); $msg='Convocation envoyée aux enseignants et assistants.'; }
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>Convocation</title><link rel="stylesheet" href="assets/css/style.css"></head><body><main class="content page solo"><h1>🏛️ Convocation de réunion</h1><a href="dashboard.php">Retour</a><?php if($msg): ?><div class="alert ok"><?=h($msg)?></div><?php endif; ?><form method="post" class="card form"><label>Objet de la réunion</label><input name="objet" required><label>Date et heure prévues</label><input type="datetime-local" name="date_heure" required><label>Lieu ou lien</label><input name="lieu" required><label>Message facultatif</label><textarea name="message"></textarea><button>Envoyer la convocation</button></form></main></body></html>
