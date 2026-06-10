<?php
final class Session {
    public static function demarrer(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax']);
            session_start();
        }
    }
    public static function connecter(array $user): void {
        self::demarrer(); session_regenerate_id(true);
        $_SESSION['user'] = ['id'=>(int)$user['id'], 'nom'=>$user['nom'], 'identifiant'=>$user['identifiant'], 'role'=>$user['role'], 'promotion_id'=>$user['promotion_id'] ?? null];
    }
    public static function user(): ?array { self::demarrer(); return $_SESSION['user'] ?? null; }
    public static function requireLogin(): array { $u=self::user(); if(!$u){ header('Location: login.php'); exit; } return $u; }
    public static function requireRole(array $roles): array { $u=self::requireLogin(); if(!in_array($u['role'],$roles,true)){ header('Location: dashboard.php?erreur=acces_refuse'); exit; } return $u; }
    public static function deconnecter(): void { self::demarrer(); $_SESSION=[]; if(session_id()) session_destroy(); }
}
