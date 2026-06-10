<?php
require_once __DIR__.'/BaseDeDonnees.php';
final class Convocation {
    public function __construct(private int $auteurId, private string $objet, private string $dateHeure, private string $lieu, private ?string $message){}
    public function envoyer(): bool {
        $db=BaseDeDonnees::connexion(); $db->beginTransaction();
        $st=$db->prepare('INSERT INTO convocations(auteur_id,objet,date_heure,lieu,message,created_at) VALUES(?,?,?,?,?,NOW())');
        $st->execute([$this->auteurId,$this->objet,$this->dateHeure,$this->lieu,$this->message]); $cid=(int)$db->lastInsertId();
        $users=$db->query("SELECT id FROM users WHERE role IN ('enseignant','assistant')")->fetchAll();
        $ins=$db->prepare('INSERT INTO convocation_destinataires(convocation_id,user_id) VALUES(?,?)');
        foreach($users as $u){ $ins->execute([$cid,$u['id']]); }
        $db->commit(); return true;
    }
}
