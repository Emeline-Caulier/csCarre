<?php
// Recherche publique de produits (barre de recherche côté client).
// Paramètre GET : q (string, min 2 caractères).
// Recherche insensible aux accents (unaccent) et à la casse (LOWER).
// Format identique à search.php pour pouvoir mutualiser le composant d'affichage JS.
header('Content-Type: application/json; charset=utf-8');

require '../utils/all_includes.php';

$q = trim($_GET['q'] ?? '');
$produitDAO = new ProduitDAO($cnx);

$produits = (mb_strlen($q) >= 2) ? $produitDAO->rechercherProduits($q) : [];

$formates = array_map(function($p) {
    return [
        'type' => 'produit',
        'icon' => 'bi-gem',
        'label' => $p->nom_produit,
        'sub' => $p->nom_categorie . ' - Réf. #' . $p->id_produit,
        'url' => 'index_.php?page=produit.php&id=' . $p->id_produit
    ];
}, $produits);

echo json_encode($formates);
