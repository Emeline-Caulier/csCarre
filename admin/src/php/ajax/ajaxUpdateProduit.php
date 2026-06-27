<?php
// Modifie un champ unique d'un produit (édition inline du tableau admin).
// Paramètres GET : id_produit (int), champ (nom de colonne), nouveau (valeur).
// Le champ "prix" subit un nettoyage préalable (€, espaces, virgule -> point).
// Toutes les écritures passent par ProduitDAO::modifierChamp(), qui appelle la
// fonction PL/pgSQL update_champ_produit() utilisant EXECUTE format() avec %I/%L.
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(false);
    exit;
}

require('../utils/all_includes.php');

$produitDAO = new ProduitDAO($cnx);
$champ = $_GET['champ'] ?? '';
$nouveau = $_GET['nouveau'] ?? '';
$id_produit = (int)($_GET['id_produit'] ?? 0);

// Nettoyage spécifique au prix (saisie de type "12,50 €")
if ($champ === 'prix') {
    $nouveau = str_replace(['€', ' '], '', $nouveau);
    $nouveau = str_replace(',', '.', $nouveau);
}

$retour = false;
if ($id_produit > 0 && $champ !== '') {
    $retour = $produitDAO->modifierChamp($id_produit, $champ, $nouveau);
}

echo json_encode($retour);
