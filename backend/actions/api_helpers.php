<?php
function json_response(array $data, int $code=200): void { http_response_code($code); header('Content-Type: application/json; charset=utf-8'); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }
function wants_json(): bool { return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') || ($_POST['ajax'] ?? '') === '1'; }
