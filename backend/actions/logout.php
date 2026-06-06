<?php
require_once __DIR__.'/../classes/Session.php';
Session::deconnecter();
header('Location: ../../login.html');
