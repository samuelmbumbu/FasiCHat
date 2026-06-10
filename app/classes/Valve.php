<?php
require_once __DIR__.'/BaseDeDonnees.php';
final class AnnonceValve { public function __construct(public string $titre, public string $contenu, public string $categorie='info', public ?string $expiration=null){} }
final class Valve {
    public function publier(int $auteurId, AnnonceValve $a): bool { $st=BaseDeDonnees::connexion()->prepare('INSERT INTO annonces_valve(auteur_id,titre,contenu,categorie,date_publication,date_expiration) VALUES(?,?,?,?,NOW(),?)'); return $st->execute([$auteurId,$a->titre,$a->contenu,$a->categorie,$a->expiration]); }
    public function modifier(int $id, AnnonceValve $a): bool { $st=BaseDeDonnees::connexion()->prepare('UPDATE annonces_valve SET titre=?, contenu=?, categorie=?, date_expiration=? WHERE id=?'); return $st->execute([$a->titre,$a->contenu,$a->categorie,$a->expiration,$id]); }
    public function supprimer(int $id): bool { $st=BaseDeDonnees::connexion()->prepare('DELETE FROM annonces_valve WHERE id=?'); return $st->execute([$id]); }
    public function lister(?string $categorie=null): array { $sql='SELECT a.*, u.nom auteur FROM annonces_valve a JOIN users u ON u.id=a.auteur_id WHERE date_expiration IS NULL OR date_expiration>=CURDATE()'; $params=[]; if($categorie){$sql.=' AND categorie=?';$params[]=$categorie;} $sql.=' ORDER BY a.date_publication DESC'; $st=BaseDeDonnees::connexion()->prepare($sql); $st->execute($params); return $st->fetchAll(); }
}
