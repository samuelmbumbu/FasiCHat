<?php
class Session {
    public static function demarrer(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
            session_start();
        }
    }
    public static function connecter(array $user): void {
        self::demarrer();
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'=>(int)$user['id'],
            'nom'=>$user['nom'],
            'identifiant'=>$user['identifiant'],
            'role'=>$user['role'],
            'promotion_id'=>$user['promotion_id'] ?? null
        ];
    }
    public static function user(): ?array { self::demarrer(); return $_SESSION['user'] ?? null; }
    public static function requireLogin(): void {
        self::demarrer();
        if (!isset($_SESSION['user'])) {
            if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/')) { }
            header('Location: ../../login.html'); exit;
        }
    }
    public static function roleIs(array $roles): bool { $u=self::user(); return $u && in_array($u['role'], $roles, true); }
    public static function deconnecter(): void { self::demarrer(); $_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'] ?? '', $p['secure'] ?? false, $p['httponly'] ?? true); } session_destroy(); }
}
