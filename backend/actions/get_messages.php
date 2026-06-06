<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/../classes/Message.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer(); Session::requireLogin();
$coursId = (int)($_GET['cours_id'] ?? 1);
json_response(['ok'=>true,'messages'=>Message::lister($coursId)]);
