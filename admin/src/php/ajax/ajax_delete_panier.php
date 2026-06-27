<?php
// Retire un produit du panier de la session courante.
// Déclenché par le bouton "corbeille" de chaque ligne de la page panier.
// En cas de succès, le JS recharge la page pour recalculer le total et les frais.
// Paramètre POST : id_produit (int).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../utils/all_includes.php";

header('Content-Type: application/json');

$id_produit = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

if ($id_produit <= 0) {
    echo json_encode(['success' => false, 'message' => 'Produit invalide.']);
    exit;
}

$panierDAO = new PanierDAO($cnx);
$id_panier = $panierDAO->getPanier($id_session, $id_client);

if ($id_panier) {
    $deleteOk = $panierDAO->supprimerProduit($id_panier, $id_produit);
    if ($deleteOk) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Panier introuvable.']);
}
