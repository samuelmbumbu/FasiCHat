<?php
require_once __DIR__.'/../config.php';
abstract class Fichier { protected array $file; public function __construct(array $file){$this->file=$file;} abstract public function typeLogique(): string; }
class Image extends Fichier { public function typeLogique(): string{return 'image';} }
class Video extends Fichier { public function typeLogique(): string{return 'video';} }
class Document extends Fichier { public function typeLogique(): string{return 'document';} }
class Audio extends Fichier { public function typeLogique(): string{return 'audio';} }
final class GestionFichier {
    private array $mimes = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/png','image/gif','video/mp4','video/webm','audio/webm','audio/mpeg','audio/wav'];
    public function sauvegarder(array $file): array {
        if(($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new RuntimeException('Erreur lors du transfert du fichier.');
        if(($file['size'] ?? 0) > MAX_UPLOAD_SIZE) throw new RuntimeException('La taille maximale est de 20 Mo.');
        $finfo = new finfo(FILEINFO_MIME_TYPE); $mime = $finfo->file($file['tmp_name']);
        if(!in_array($mime,$this->mimes,true)) throw new RuntimeException('Type de fichier non autorisé : '.$mime);
        if(!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR,0775,true);
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $safe = bin2hex(random_bytes(8)).($ext?'.'.$ext:'');
        $target = UPLOAD_DIR.$safe;
        if(!move_uploaded_file($file['tmp_name'],$target)) throw new RuntimeException('Impossible de sauvegarder le fichier.');
        $type = str_starts_with($mime,'image/')?'image':(str_starts_with($mime,'video/')?'video':(str_starts_with($mime,'audio/')?'audio':'document'));
        if($type==='image' && function_exists('imagecreatefromjpeg')) $this->compresserImage($target,$mime);
        return ['nom_original'=>$file['name'], 'nom_stocke'=>$safe, 'url'=>UPLOAD_URL.$safe, 'mime'=>$mime, 'type'=>$type, 'taille'=>(int)$file['size']];
    }
    private function compresserImage(string $path, string $mime): void {
        if($mime==='image/jpeg'){ $img=@imagecreatefromjpeg($path); if($img){ imagejpeg($img,$path,75); imagedestroy($img);} }
        if($mime==='image/png'){ $img=@imagecreatefrompng($path); if($img){ imagepng($img,$path,7); imagedestroy($img);} }
    }
}
