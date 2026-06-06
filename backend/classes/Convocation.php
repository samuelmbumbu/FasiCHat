<?php
require_once __DIR__.'/BaseDeDonnees.php';
class Convocation {
    public function __construct(private int $senderId, private string $objet, private string $dateHeure, private string $lieu, private ?string $message) {}
    public function enregistrer(): bool {
        $pdo = BaseDeDonnees::connexion();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO convocations(sender_id, objet, date_heure, lieu, message) VALUES(?,?,?,?,?)');
        $stmt->execute([$this->senderId, trim($this->objet), $this->dateHeure, trim($this->lieu), trim($this->message ?? '')]);
        $convocationId = (int)$pdo->lastInsertId();
        $dest = $pdo->query("SELECT id FROM users WHERE role IN ('enseignant','assistant')")->fetchAll();
        $ins = $pdo->prepare('INSERT INTO convocation_destinataires(convocation_id, user_id) VALUES(?,?)');
        foreach ($dest as $d) $ins->execute([$convocationId, $d['id']]);
        return $pdo->commit();
    }
}
