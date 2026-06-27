<?php

declare(strict_types=1);

// Étend Produit en ajoutant le nom de la catégorie issue de la jointure
// Correspond à la vue vue_produit_categorie (produit + categorie)
class ProduitCategorie extends Produit
{
    public function __construct(
        int $id_produit,
        int $id_categorie,
        string $nom_produit,
        string $description,
        string $prix,
        int $stock,
        public readonly string $nom_categorie,
        ?string $photo_principale = null,
        bool $est_nouveau = false
    ) {
        parent::__construct(
            $id_produit,
            $id_categorie,
            $nom_produit,
            $description,
            $prix,
            $stock,
            $photo_principale,
            $est_nouveau
        );
    }
}
