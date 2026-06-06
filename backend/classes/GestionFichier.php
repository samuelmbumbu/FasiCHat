<?php
require_once __DIR__.'/../config.php';

abstract class Fichier {
    public function __construct(protected array $file) {}
    abstract public function sauvegarder(): array;
    protected function validerBase(array $typesAutorises): string {
        if (($this->file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new RuntimeException('Aucun fichier valide reçu.');
        if (($this->file['size'] ?? 0) > MAX_UPLOAD_SIZE) throw new RuntimeException('Fichier supérieur à 20 Mo.');
        $mime = mime_content_type($this->file['tmp_name']);
        if (!in_array($mime, $typesAutorises, true)) throw new RuntimeException('Type de fichier non autorisé : '.$mime);
        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
        return $mime;
    }
    protected function nomFinal(): array {
        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $name = bin2hex(random_bytes(12)) . ($ext ? '.'.$ext : '');
        return [$name, UPLOAD_DIR.$name, 'backend/uploads/'.$name];
    }
}

class Image extends Fichier {
    public function sauvegarder(): array {
        $mime = $this->validerBase(['image/jpeg','image/png','image/webp']);
        [$name,$path,$url] = $this->nomFinal();
        if (extension_loaded('gd')) {
            $img = match($mime) { 'image/png'=>imagecreatefrompng($this->file['tmp_name']), 'image/webp'=>imagecreatefromwebp($this->file['tmp_name']), default=>imagecreatefromjpeg($this->file['tmp_name']) };
            imagejpeg($img, $path, 75); imagedestroy($img);
        } else move_uploaded_file($this->file['tmp_name'], $path);
        return ['url'=>$url,'nom'=>$this->file['name'],'mime'=>$mime,'taille'=>(int)$this->file['size'],'type'=>'image'];
    }
}
class Video extends Fichier {
    public function sauvegarder(): array {
        // Compression vidéo réelle nécessite FFmpeg. Ici on valide et on stocke proprement le fichier.
        $mime = $this->validerBase(['video/mp4','video/webm','video/quicktime']);
        [$name,$path,$url] = $this->nomFinal(); move_uploaded_file($this->file['tmp_name'], $path);
        return ['url'=>$url,'nom'=>$this->file['name'],'mime'=>$mime,'taille'=>(int)$this->file['size'],'type'=>'video'];
    }
}
class Document extends Fichier {
    public function sauvegarder(): array {
        $mime = $this->validerBase(['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        [$name,$path,$url] = $this->nomFinal(); move_uploaded_file($this->file['tmp_name'], $path);
        return ['url'=>$url,'nom'=>$this->file['name'],'mime'=>$mime,'taille'=>(int)$this->file['size'],'type'=>'document'];
    }
}
class Audio extends Fichier {
    public function sauvegarder(): array {
        $mime = $this->validerBase(['audio/mpeg','audio/webm','audio/wav','audio/mp4','audio/ogg']);
        [$name,$path,$url] = $this->nomFinal(); move_uploaded_file($this->file['tmp_name'], $path);
        return ['url'=>$url,'nom'=>$this->file['name'] ?: 'audio.webm','mime'=>$mime,'taille'=>(int)$this->file['size'],'type'=>'audio'];
    }
}
class GestionFichier {
    public function sauvegarder(array $file): array {
        $mime = mime_content_type($file['tmp_name'] ?? '') ?: '';
        if (str_starts_with($mime, 'image/')) return (new Image($file))->sauvegarder();
        if (str_starts_with($mime, 'video/')) return (new Video($file))->sauvegarder();
        if (str_starts_with($mime, 'audio/')) return (new Audio($file))->sauvegarder();
        return (new Document($file))->sauvegarder();
    }
}
