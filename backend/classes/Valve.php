<?php
require_once __DIR__.'/BaseDeDonnees.php';
class Valve {
    private function verifierApparitaire(string $role): void { if ($role !== 'apparitaire') { http_response_code(403); exit('Action réservée à l’Apparitaire.'); } }
    public function publier(int $auteurId, string $role, string $titre, string $contenu, ?string $expiration=null): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('INSERT INTO annonces_valve(auteur_id,titre,contenu,date_expiration) VALUES(?,?,?,?)');
        return $stmt->execute([$auteurId, trim($titre), trim($contenu), $expiration ?: null]);
    }
    public function modifier(int $id, string $role, string $titre, string $contenu): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('UPDATE annonces_valve SET titre=?, contenu=? WHERE id=?');
        return $stmt->execute([trim($titre), trim($contenu), $id]);
    }
    public function supprimer(int $id, string $role): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('DELETE FROM annonces_valve WHERE id=?');
        return $stmt->execute([$id]);
    }
    public function lister(): array { return BaseDeDonnees::connexion()->query('SELECT a.*, u.nom auteur FROM annonces_valve a JOIN users u ON u.id=a.auteur_id ORDER BY a.date_publication DESC')->fetchAll(); }
}
