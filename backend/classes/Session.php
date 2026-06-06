<?php
class Session {
    public static function demarrer(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
            session_start();
        }
    }
    public static function connecter(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id'=>$user['id'], 'nom'=>$user['nom'], 'identifiant'=>$user['identifiant'], 'role'=>$user['role']];
    }
    public static function user(): ?array { self::demarrer(); return $_SESSION['user'] ?? null; }
    public static function requireLogin(): void { self::demarrer(); if (!isset($_SESSION['user'])) { header('Location: ../login.html'); exit; } }
    public static function roleIs(array $roles): bool { $u=self::user(); return $u && in_array($u['role'], $roles, true); }
    public static function deconnecter(): void { self::demarrer(); $_SESSION=[]; session_destroy(); }
}
