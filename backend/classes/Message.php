<?php
require_once __DIR__.'/BaseDeDonnees.php';
abstract class Message {
    protected int $senderId; protected string $contenu; protected ?int $receiverId; protected ?int $coursId; protected ?array $fichier;
    public function __construct(int $senderId, string $contenu, ?int $receiverId=null, ?int $coursId=null, ?array $fichier=null){
        $this->senderId=$senderId; $this->contenu=trim($contenu); $this->receiverId=$receiverId; $this->coursId=$coursId; $this->fichier=$fichier;
    }
    abstract protected function type(): string;
    public function envoyer(): bool {
        $stmt = BaseDeDonnees::connexion()->prepare('INSERT INTO messages(sender_id, receiver_id, cours_id, type_message, contenu, fichier_url, fichier_nom, fichier_type, fichier_taille) VALUES(?,?,?,?,?,?,?,?,?)');
        return $stmt->execute([
            $this->senderId, $this->receiverId ?: null, $this->coursId ?: null, $this->type(), $this->contenu,
            $this->fichier['url'] ?? null, $this->fichier['nom'] ?? null, $this->fichier['type'] ?? null, $this->fichier['taille'] ?? null
        ]);
    }
    public static function lister(?int $coursId=1): array {
        $stmt = BaseDeDonnees::connexion()->prepare('SELECT m.*, u.nom auteur, u.role FROM messages m JOIN users u ON u.id=m.sender_id WHERE (m.cours_id = :cours OR m.cours_id IS NULL) ORDER BY m.created_at ASC LIMIT 100');
        $stmt->execute(['cours'=>$coursId]); return $stmt->fetchAll();
    }
}
class MessagePrive extends Message { protected function type(): string { return 'prive'; } }
class MessagePublic extends Message { protected function type(): string { return 'public'; } }
class MurPedagogique extends Message { protected function type(): string { return 'mur_pedagogique'; } }
