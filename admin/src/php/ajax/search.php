<?php
// Recherche globale dans l'interface admin.
// Paramètre GET : q (string, min 2 caractères).
// Recherche dans 3 entités, max 4 résultats chacune, total tronqué à 10 :
//   - Clients : nom, prénom, email (insensible aux accents via unaccent)
//   - Produits : nom, ou id exact si la saisie est numérique
//   - Commandes : id exact, ou nom/prénom du client
// Les trois blocs sont indépendants : une erreur sur l'un n'empêche pas les autres.
// Accès : admin connecté uniquement.
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode([]);
    exit;
}

require '../utils/all_includes.php';

$q = trim($_GET['q'] ?? '');
$resultatsInitiaux = [];

if (mb_strlen($q) >= 2) {
    foreach ((new ProduitDAO($cnx))->rechercherProduits($q) as $p) {
        $resultatsInitiaux[] = [
            'type' => 'produit',
            'icon' => 'bi-gem',
            'label' => $p->nom_produit,
            'sub' => 'Réf. #' . $p->id_produit,
            'url'   => 'index_.php?page=produit.php&detail=' . $p->id_produit . '&id_cat=' . $p->id_categorie
        ];
    }
    foreach ((new ClientDAO($cnx))->rechercher($q) as $c) {
        $resultatsInitiaux[] = [
            'type' => 'client',
            'icon' => 'bi-person',
            'label' => $c->prenom_client . ' ' . $c->nom_client,
            'sub' => $c->email_client,
            'url'   => 'index_.php?page=client.php&detail=' . $c->id_client
        ];
    }
    foreach ((new CommandeDAO($cnx))->rechercher($q) as $cmd) {
        $resultatsInitiaux[] = [
            'type' => 'commande',
            'icon' => 'bi-bag',
            'label' => 'Commande #' . $cmd->id_commande,
            'sub' => $cmd->prenom_client . ' ' . $cmd->nom_client,
            'url'   => 'index_.php?page=commande.php&detail=' . $cmd->id_commande
        ];
    }
}

echo json_encode(array_slice($resultatsInitiaux, 0, 10));
