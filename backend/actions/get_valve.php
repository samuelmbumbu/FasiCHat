<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Valve.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
$categorie = $_GET['categorie'] ?? null;
$annonces = (new Valve())->lister($categorie);
json_response(['ok'=>true,'annonces'=>$annonces]);
