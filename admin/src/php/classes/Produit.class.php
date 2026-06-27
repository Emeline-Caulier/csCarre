<?php

declare(strict_types=1);

// Représente un produit du catalogue (sans jointure catégorie)
// Pour un produit avec son nom de catégorie, utiliser ProduitCategorie
class Produit
{
    public function __construct(
        public readonly int $id_produit,
        public readonly int $id_categorie,
        public readonly string $nom_produit,
        public readonly string $description,
        public readonly string $prix,
        public readonly int $stock,
        public readonly ?string $photo_principale = null,
        public readonly bool $est_nouveau = false
    ) {}
}
