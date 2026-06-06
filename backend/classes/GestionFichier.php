<?php
require_once __DIR__.'/../config.php';
class GestionFichier {
    private array $typesAutorises = ['image/jpeg','image/png','image/webp','video/mp4','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','audio/mpeg','audio/webm'];
    public function sauvegarder(array $file): string {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new RuntimeException('Upload invalide.');
        if ($file['size'] > MAX_UPLOAD_SIZE) throw new RuntimeException('Fichier supérieur à 20 Mo.');
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $this->typesAutorises, true)) throw new RuntimeException('Type de fichier non autorisé.');
        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(12)) . '.' . strtolower($ext);
        $path = UPLOAD_DIR . $name;
        if (str_starts_with($mime, 'image/')) { $this->compresserImage($file['tmp_name'], $path, $mime); }
        else { move_uploaded_file($file['tmp_name'], $path); }
        return 'backend/uploads/' . $name;
    }
    private function compresserImage(string $src, string $dest, string $mime): void {
        if (!extension_loaded('gd')) { move_uploaded_file($src, $dest); return; }
        $img = match($mime) { 'image/png'=>imagecreatefrompng($src), 'image/webp'=>imagecreatefromwebp($src), default=>imagecreatefromjpeg($src) };
        imagejpeg($img, $dest, 75); imagedestroy($img);
    }
}
