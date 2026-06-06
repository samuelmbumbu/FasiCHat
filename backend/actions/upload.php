<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/GestionFichier.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
try { $file = (new GestionFichier())->sauvegarder($_FILES['fichier']); json_response(['ok'=>true,'file'=>$file]); }
catch(Throwable $e){ json_response(['ok'=>false,'message'=>$e->getMessage()], 400); }
