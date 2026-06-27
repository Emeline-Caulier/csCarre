<?php
// Met à jour la quantité d'un produit dans le panier.
// Déclenché par les boutons + et - de la page panier.
// Vérifie le stock disponible avant d'appliquer la modification.
// En cas de succès, le JS recharge la page pour recalculer le total.
// Paramètres POST : id_produit (int), quantite (int).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../utils/all_includes.php";

header('Content-Type: application/json');

$id_produit = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
$quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 0;

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

if ($id_produit <= 0 || $quantite <= 0) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

$panierDAO = new PanierDAO($cnx);
$id_panier = $panierDAO->getPanier($id_session, $id_client);

if (!$id_panier) {
    echo json_encode(['success' => false, 'message' => 'Panier introuvable.']);
    exit;
}

$infosStock = $panierDAO->getStockEtQtePanier($id_panier, $id_produit);
if ($infosStock === null) {
    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
    exit;
}
if ($quantite > $infosStock['stock']) {
    echo json_encode(['success' => false, 'message' => 'Stock insuffisant : seulement ' . $infosStock['stock'] . ' disponible(s).']);
    exit;
}

if ($panierDAO->updateQuantite($id_panier, $id_produit, $quantite)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
}
