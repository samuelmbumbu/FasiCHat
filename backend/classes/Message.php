<?php
require_once __DIR__.'/BaseDeDonnees.php';
abstract class Message {
    protected int $senderId; protected string $contenu; protected ?int $receiverId; protected ?int $coursId;
    public function __construct(int $senderId, string $contenu, ?int $receiverId=null, ?int $coursId=null){ $this->senderId=$senderId; $this->contenu=trim($contenu); $this->receiverId=$receiverId; $this->coursId=$coursId; }
    abstract protected function type(): string;
    public function envoyer(): bool {
        $stmt = BaseDeDonnees::connexion()->prepare('INSERT INTO messages(sender_id, receiver_id, cours_id, type_message, contenu) VALUES(?,?,?,?,?)');
        return $stmt->execute([$this->senderId, $this->receiverId, $this->coursId, $this->type(), $this->contenu]);
    }
}
class MessagePrive extends Message { protected function type(): string { return 'prive'; } }
class MessagePublic extends Message { protected function type(): string { return 'public'; } }
class MurPedagogique extends Message { protected function type(): string { return 'mur_pedagogique'; } }
