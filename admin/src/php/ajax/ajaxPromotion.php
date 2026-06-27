<?php
// Gestion des promotions produit côté admin.
// Paramètre commun POST : action_promo (ajouter | supprimer | modifier).
//   - ajouter   : id_produit, taux_reduction (0.01..0.99), date_debut, date_fin
//                 Vérifie l'absence de promo active sur ce produit avant d'insérer.
//   - supprimer : id_promotion. Le JS retire la ligne du tableau avec un fadeOut.
//   - modifier  : id_promotion, champ (taux_reduction|date_debut|date_fin), nouveau
//                 Validation du nom de champ par whitelist côté PHP ET côté PL/pgSQL.
// Accès : admin connecté uniquement.
session_start();
header('Content-Type: application/json');
require('../utils/all_includes.php');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$promotionDAO = new PromotionDAO($cnx);
$action = $_POST['action_promo'] ?? '';

if ($action === 'ajouter') {
    $id_produit = (int)($_POST['id_produit'] ?? 0);
    $taux = (float)($_POST['taux_reduction'] ?? 0);
    $debut = $_POST['date_debut'] ?? '';
    $fin = $_POST['date_fin'] ?? '';

    if (!$id_produit || !$taux || !$debut || !$fin) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    // Un produit ne peut avoir qu'une promo active
    if ($promotionDAO->existeDeja($id_produit)) {
        echo json_encode(['success' => false, 'message' => 'Ce produit est déjà en promotion dans la vitrine !']);
        exit;
    }

    $ok = $promotionDAO->insert($id_produit, $taux, $debut, $fin);
    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'insertion en base.']);
        exit;
    }

    // On recharge les détails de la promotion insérée pour les renvoyer au JS,
    // qui construit dynamiquement la ligne du tableau sans rechargement de page.
    $promo = $promotionDAO->getDetailsByProduitId($id_produit);
    if (!$promo) {
        echo json_encode(['success' => false, 'message' => 'Insertion réussie mais impossible de récupérer les données.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Promotion ajoutée à la vitrine !', 'data' => $promo]);

} elseif ($action === 'supprimer') {
    $id_promo = (int)($_POST['id_promotion'] ?? 0);
    if (!$id_promo) {
        echo json_encode(['success' => false, 'message' => 'Identifiant manquant.']);
        exit;
    }

    $ok = $promotionDAO->delete($id_promo);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Promotion retirée de la vitrine.' : 'Erreur lors de la suppression.'
    ]);

} elseif ($action === 'modifier') {
    $id_promo = (int)($_POST['id_promotion'] ?? 0);
    $champ = $_POST['champ'] ?? '';
    $nouveau = $_POST['nouveau'] ?? '';

    // Liste blanche des champs modifiables
    $champsAutorises = ['taux_reduction', 'date_debut', 'date_fin'];
    if (!$id_promo || !in_array($champ, $champsAutorises, true) || $nouveau === '') {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
        exit;
    }

    $ok = $promotionDAO->updateChamp($id_promo, $champ, $nouveau);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Modification enregistrée.' : 'Erreur lors de la modification.'
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Action non reconnue.']);
}
