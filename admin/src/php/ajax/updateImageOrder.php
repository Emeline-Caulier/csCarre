<?php
// Sauvegarde le nouvel ordre des images produit après un glisser-déposer.
// Méthode : POST. Corps JSON : { "ids": [3, 1, 4, 2] }.
// L'ordre du tableau est le nouvel ordre visuel : ids[0] => ordre=1, ids[1] => ordre=2, etc.
// Accès : admin connecté uniquement.
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['ok' => false, 'error' => 'Non autorisé']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false]);
    exit;
}

require('../utils/all_includes.php');

$donnees = json_decode(file_get_contents('php://input'), true);
$tableauIds = array_map('intval', $donnees['ids'] ?? []);

if (empty($tableauIds)) {
    echo json_encode(['ok' => false]);
    exit;
}

$produitDAO = new ProduitDAO($cnx);
$toutOk = true;
foreach ($tableauIds as $i => $idImage) {
    if (!$produitDAO->modifierOrdreImage($idImage, $i + 1)) {
        $toutOk = false;
    }
}

echo json_encode(['ok' => $toutOk]);
