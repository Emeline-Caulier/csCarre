<?php

declare(strict_types=1);

// Représente un avis client sur un produit, issu de la vue v_avis_details
class Avis
{
    public function __construct(
        public readonly int $id_avis,
        public readonly string $prenom_client,
        public readonly string $nom_client,
        public readonly string $nom_produit,
        public readonly int $note_etoiles,
        public readonly string $commentaire,
        public readonly string $date_avis,
        public readonly ?string $photo,
        public readonly string $modere,
    ) {}
}
