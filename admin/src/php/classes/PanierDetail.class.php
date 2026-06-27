<?php

class PanierDetail {
    public function __construct(
        public readonly ?int $id_produit,
        public readonly ?int $quantite,
        public readonly ?string $nom_produit,
        public readonly ?string $prix,
        public readonly ?string $image_url,
        public readonly ?int $stock = null
    ) {}
}