<?php
// Gestion de la section "Nouvelle collection" côté admin.
// Paramètre commun : action (rechercher | ajouter | supprimer | modifier_nom).
//   - rechercher (GET, q)         : produits non encore en nouveauté, min 2 caractères
//   - ajouter (POST, id_produit)  : marque le produit comme nouveau (est_nouveau = true)
//   - supprimer (POST, id_produit): retire le produit de la collection
//   - modifier_nom (POST, nom)    : met à jour le libellé de la section
// Accès : admin connecté uniquement.
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

require '../utils/all_includes.php';

$produitDAO = new ProduitDAO($cnx);
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'rechercher':
        $q = trim($_GET['q'] ?? '');
        if (mb_strlen($q) < 2) {
            echo json_encode([]);
        } else {
            echo json_encode($produitDAO->rechercherPourNouveaute($q));
        }
        break;

    case 'ajouter':
        $id = (int)($_POST['id_produit'] ?? 0);
        $success = false;
        $data = null;

        if ($id > 0) {
            $success = $produitDAO->modifierStatutNouveau($id, true);
            if ($success) {
                $data = $produitDAO->getInfosPourNouveaute($id);
            }
        }
        echo json_encode(['success' => $success, 'data' => $data]);
        break;

    case 'supprimer':
        $id = (int)($_POST['id_produit'] ?? 0);
        $success = ($id > 0) ? $produitDAO->modifierStatutNouveau($id, false) : false;
        echo json_encode(['success' => $success]);
        break;

    case 'modifier_nom':
        $nom = trim($_POST['nom'] ?? '');
        $success = false;
        if ($nom !== '') {
            try {
                $configDAO = new ConfigurationDAO($cnx);
                $configDAO->update('nom_nouvelle_collection', $nom);
                $success = true;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'action inconnue']);
}
