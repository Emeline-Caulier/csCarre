<?php
// Retire un produit de la vitrine (soft delete : en_vitrine = false).
// Le produit n'est pas physiquement supprimé pour préserver l'historique des
// commandes qui y sont liées, il est simplement masqué côté front.
// Accès : admin connecté uniquement. Méthode : POST.
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(false);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(false);
    exit;
}

require('../utils/all_includes.php');

$id = (int)($_POST['id_produit'] ?? 0);
if ($id <= 0) {
    echo json_encode(false);
    exit;
}

$produitDAO = new ProduitDAO($cnx);
echo json_encode($produitDAO->retirerDeLaVitrine($id));
