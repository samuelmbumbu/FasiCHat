<?php
require_once __DIR__.'/BaseDeDonnees.php';
class Valve {
    private function verifierApparitaire(string $role): void { if ($role !== 'apparitaire') { http_response_code(403); throw new RuntimeException('Action réservée à l’Apparitaire.'); } }
    public function publier(int $auteurId, string $role, string $titre, string $contenu, string $categorie='info', ?string $expiration=null, ?array $fichier=null): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('INSERT INTO annonces_valve(auteur_id,titre,contenu,categorie,date_expiration,fichier_url,fichier_nom) VALUES(?,?,?,?,?,?,?)');
        return $stmt->execute([$auteurId, trim($titre), trim($contenu), $categorie ?: 'info', $expiration ?: null, $fichier['url'] ?? null, $fichier['nom'] ?? null]);
    }
    public function modifier(int $id, string $role, string $titre, string $contenu, string $categorie='info'): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('UPDATE annonces_valve SET titre=?, contenu=?, categorie=? WHERE id=?');
        return $stmt->execute([trim($titre), trim($contenu), $categorie, $id]);
    }
    public function supprimer(int $id, string $role): bool {
        $this->verifierApparitaire($role);
        $stmt = BaseDeDonnees::connexion()->prepare('DELETE FROM annonces_valve WHERE id=?');
        return $stmt->execute([$id]);
    }
    public function lister(?string $categorie=null): array {
        $sql = 'SELECT a.*, u.nom auteur FROM annonces_valve a JOIN users u ON u.id=a.auteur_id WHERE (a.date_expiration IS NULL OR a.date_expiration >= CURDATE())';
        $params=[];
        if ($categorie && $categorie !== 'toutes') { $sql .= ' AND a.categorie = ?'; $params[]=$categorie; }
        $sql .= ' ORDER BY a.date_publication DESC';
        $stmt=BaseDeDonnees::connexion()->prepare($sql); $stmt->execute($params); return $stmt->fetchAll();
    }
}
