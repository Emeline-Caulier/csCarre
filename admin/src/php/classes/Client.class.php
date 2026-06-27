<?php

declare(strict_types=1);

// Représente un client. Implémente JsonSerializable pour les réponses AJAX
class Client implements JsonSerializable
{
    public function __construct(
        public readonly int $id_client,
        public readonly string $nom_client,
        public readonly string $prenom_client,
        public readonly string $email_client,
        public readonly string $mot_de_passe,
        public readonly string $rue,
        public readonly string $numero,
        public readonly string $code_postal,
        public readonly string $ville,
        public readonly string $pays
    ) {}


    // Définit comment l'objet est converti en JSON par json_encode().
    // Sans cette méthode, json_encode() ne sérialise que les propriétés publiques
    // accessibles depuis l'extérieur. Avec get_object_vars($this) on récupère
    // toutes les propriétés vues depuis l'intérieur de la classe (publiques,
    // protégées et privées), ce qui garantit que unset($cl->mot_de_passe) avant
    // json_encode() (cf. ajaxCheckClient.php) fonctionne aussi sur les propriétés
    // readonly issues du constructor promotion.
    // Le retour est typé "mixed" car JsonSerializable autorise n'importe quelle
    // valeur sérialisable JSON (tableau, objet, scalaire, null).
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
