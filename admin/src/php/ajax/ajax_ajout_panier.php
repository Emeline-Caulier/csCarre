<?php
// Ajoute un produit au panier de la session courante.
// Le panier est créé automatiquement s'il n'existe pas encore (via get_ou_creer_panier).
// Si le produit est déjà présent, la quantité est incrémentée côté BDD.
// Paramètres POST : id_produit (int, requis), quantite (int, défaut 1).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../utils/all_includes.php";

header('Content-Type: application/json');

$id_produit = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
$quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

if ($id_produit <= 0) {
    echo json_encode(['success' => false, 'message' => 'Produit invalide.']);
    exit;
}

$panierDAO = new PanierDAO($cnx);
$id_panier = $panierDAO->getPanier($id_session, $id_client);

if (!$id_panier) {
    echo json_encode(['success' => false, 'message' => 'Impossible de générer votre panier.']);
    exit;
}

// On vérifie le stock avant l'ajout pour pouvoir fournir un message d'erreur précis
// à l'utilisateur si la quantité demandée dépasse le stock disponible.
$infosStock = $panierDAO->getStockEtQtePanier($id_panier, $id_produit);
if ($infosStock === null) {
    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
    exit;
}

$dispo = $infosStock['stock'] - $infosStock['qte_panier'];
if ($dispo <= 0) {
    echo json_encode(['success' => false, 'message' => 'Stock épuisé pour ce produit.']);
    exit;
}
if ($quantite > $dispo) {
    $msg = $infosStock['qte_panier'] > 0
        ? "Il ne reste que $dispo unité(s) disponible(s) (vous en avez déjà " . $infosStock['qte_panier'] . " dans votre panier)."
        : "Il ne reste que $dispo unité(s) en stock.";
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

if ($panierDAO->ajouterProduit($id_panier, $id_produit, $quantite)) {
    echo json_encode(['success' => true, 'message' => 'Produit ajouté à votre panier.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur technique lors de l\'ajout.']);
}
