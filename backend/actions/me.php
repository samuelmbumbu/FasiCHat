<?php
require_once __DIR__.'/../classes/Session.php';
require_once __DIR__.'/api_helpers.php';
Session::demarrer();
json_response(['ok'=>true,'user'=>Session::user()]);
