<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/GestionFichier.php';
Session::demarrer(); Session::requireLogin();
try { $url = (new GestionFichier())->sauvegarder($_FILES['fichier']); echo 'Fichier stocké : ' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); }
catch(Throwable $e){ http_response_code(400); echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); }
