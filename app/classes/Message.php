<?php
require_once __DIR__.'/BaseDeDonnees.php';
abstract class Message {
    protected int $expediteurId; protected string $contenu; protected ?int $destinataireId; protected ?int $coursId; protected ?array $fichier;
    public function __construct(int $expediteurId, string $contenu='', ?int $destinataireId=null, ?int $coursId=null, ?array $fichier=null){$this->expediteurId=$expediteurId;$this->contenu=$contenu;$this->destinataireId=$destinataireId;$this->coursId=$coursId;$this->fichier=$fichier;}
    abstract public function type(): string;
    public function envoyer(): int {
        $db=BaseDeDonnees::connexion();
        $db->beginTransaction();
        $st=$db->prepare('INSERT INTO messages(expediteur_id,destinataire_id,cours_id,type,contenu,created_at) VALUES(?,?,?,?,?,NOW())');
        $st->execute([$this->expediteurId,$this->destinataireId,$this->coursId,$this->type(),$this->contenu]);
        $id=(int)$db->lastInsertId();
        if($this->fichier){ $f=$this->fichier; $fs=$db->prepare('INSERT INTO fichiers(message_id,nom_original,nom_stocke,url,mime,type,taille,created_at) VALUES(?,?,?,?,?,?,?,NOW())'); $fs->execute([$id,$f['nom_original'],$f['nom_stocke'],$f['url'],$f['mime'],$f['type'],$f['taille']]); }
        $db->commit(); return $id;
    }
}
class MessagePrive extends Message { public function type(): string{return 'prive';} }
class MessagePublic extends Message { public function type(): string{return 'public';} }
class MurPedagogique extends Message { public function type(): string{return 'mur_pedagogique';} }
