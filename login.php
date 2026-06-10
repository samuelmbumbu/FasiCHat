<?php
require_once __DIR__.'/app/classes/Auth.php';
require_once __DIR__.'/app/classes/Session.php';
Session::demarrer();
if(Session::user()){ header('Location: dashboard.php'); exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if((new Auth())->login($_POST['identifiant'] ?? '', $_POST['password'] ?? '')){ header('Location: dashboard.php'); exit; }
    $error='Identifiant ou mot de passe incorrect.';
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>FasiChat Connexion</title><link rel="stylesheet" href="assets/css/style.css"></head><body class="login-bg">
<div class="login-card"><div class="brand"><div class="logo">💬</div><h1>FasiChat Classroom</h1><p>Plateforme académique PHP POO</p></div>
<?php if($error): ?><div class="alert error"><?=h($error)?></div><?php endif; ?>
<form method="post"><label>Identifiant / matricule</label><input name="identifiant" required placeholder="SI2024001 ou enseignant@faculte.edu"><label>Mot de passe</label><input type="password" name="password" required placeholder="password"><button>Se connecter ➜</button></form>
<div class="hint">Mot de passe de démonstration : <b>password</b></div></div></body></html>
