<?php
trait TraitConvocation {
    public function convoquer(string $objet, string $dateHeure, string $lieu, ?string $message): bool {
        $convocation = new Convocation($this->id, $objet, $dateHeure, $lieu, $message);
        return $convocation->enregistrer();
    }
}
