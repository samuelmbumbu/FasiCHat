<?php
abstract class Utilisateur {
    protected int $id; protected string $nom; protected string $identifiant; protected string $role; protected ?int $promotionId;
    public function __construct(array $data){ $this->id=(int)$data['id']; $this->nom=$data['nom']; $this->identifiant=$data['identifiant']; $this->role=$data['role']; $this->promotionId=isset($data['promotion_id']) ? (int)$data['promotion_id'] : null; }
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getRole(): string { return $this->role; }
    public function getPromotionId(): ?int { return $this->promotionId; }
    abstract public function peutPublierValve(): bool;
    abstract public function peutConvoquer(): bool;
    abstract public function tableauDeBord(): string;
}
class Etudiant extends Utilisateur { public function peutPublierValve(): bool {return false;} public function peutConvoquer(): bool{return false;} public function tableauDeBord(): string{return 'Espace étudiant';} }
class Enseignant extends Utilisateur { public function peutPublierValve(): bool {return false;} public function peutConvoquer(): bool{return false;} public function tableauDeBord(): string{return 'Espace enseignant';} }
class Assistant extends Enseignant { public function tableauDeBord(): string{return 'Espace assistant';} }
interface Convocable { public function convoquer(string $objet, string $dateHeure, string $lieu, ?string $message): bool; }
abstract class Administratif extends Utilisateur { public function peutPublierValve(): bool {return false;} public function peutConvoquer(): bool{return false;} public function tableauDeBord(): string{return 'Espace administratif';} }
trait TraitConvocation { public function peutConvoquer(): bool{return true;} public function convoquer(string $objet, string $dateHeure, string $lieu, ?string $message): bool { require_once __DIR__.'/Convocation.php'; return (new Convocation($this->id,$objet,$dateHeure,$lieu,$message))->envoyer(); } }
class Doyen extends Administratif implements Convocable { use TraitConvocation; public function tableauDeBord(): string{return 'Espace Doyen';} }
class ViceDoyen extends Administratif implements Convocable { use TraitConvocation; public function tableauDeBord(): string{return 'Espace Vice-Doyen';} }
class Apparitaire extends Administratif { public function peutPublierValve(): bool {return true;} public function tableauDeBord(): string{return 'Espace Apparitaire';} }
