<?php
require_once __DIR__.'/TraitConvocation.php';
require_once __DIR__.'/Utilisateur.php';
require_once __DIR__.'/Convocation.php';
class UtilisateurFactory {
    public static function creer(array $data): Utilisateur {
        return match($data['role']) {
            'etudiant' => new Etudiant($data),
            'enseignant' => new Enseignant($data),
            'assistant' => new Assistant($data),
            'doyen' => new Doyen($data),
            'vice_doyen' => new ViceDoyen($data),
            'apparitaire' => new Apparitaire($data),
            default => throw new InvalidArgumentException('Rôle inconnu')
        };
    }
}
