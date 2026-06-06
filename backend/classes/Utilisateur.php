<?php
require_once __DIR__ . '/TraitConvocation.php';
abstract class Utilisateur {
    protected int $id; protected string $nom; protected string $identifiant; protected string $role;
    public function __construct(array $data){ $this->id=(int)$data['id']; $this->nom=$data['nom']; $this->identifiant=$data['identifiant']; $this->role=$data['role']; }
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getRole(): string { return $this->role; }
    abstract public function tableauDeBord(): string;
}
class Etudiant extends Utilisateur { public function tableauDeBord(): string { return 'dashboard_etudiant.html'; } }
class Enseignant extends Utilisateur { public function tableauDeBord(): string { return 'dashboard_enseignant.html'; } }
class Assistant extends Enseignant { public function tableauDeBord(): string { return 'dashboard_enseignant.html'; } }
interface Convocable { public function convoquer(string $objet, string $dateHeure, string $lieu, ?string $message): bool; }
abstract class Administratif extends Utilisateur { public function tableauDeBord(): string { return 'dashboard_admin.html'; } }
class Doyen extends Administratif implements Convocable { use TraitConvocation; }
class ViceDoyen extends Administratif implements Convocable { use TraitConvocation; public function tableauDeBord(): string { return 'dashboard_vicedoyen.html'; } }
class Apparitaire extends Administratif { public function tableauDeBord(): string { return 'dashboard_apparitaire.html'; } }
