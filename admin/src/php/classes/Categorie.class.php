<?php

declare(strict_types=1);

// Représente une catégorie de produit
class Categorie
{
    public function __construct(
        public readonly int $id_categorie,
        public readonly string $nom_categorie
    ) {}
}
