<?php
// Ajoute ou retire un produit de la liste d'envie (toggle).
// Paramètre POST : id_produit (int).
// Réponse JSON :
//   - { success: true, actif: true,  message } si le produit a été ajouté
//   - { success: true, actif: false, message } si le produit a été retiré
//   - { success: false, message }              en cas d'erreur (id invalide ou erreur BDD)
// Le JS utilise le champ "actif" pour mettre à jour l'icône cœur, et sur la page
// favoris pour retirer la carte du DOM sans recharger.
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../utils/all_includes.php";

header('Content-Type: application/json');

$id_produit = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;

if ($id_produit <= 0) {
    echo json_encode(['success' => false, 'message' => 'Produit invalide.']);
    exit;
}

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

$listeEnvieDAO = new ListeEnvieDAO($cnx);
$actif = $listeEnvieDAO->toggle($id_session, $id_produit, $id_client);

if ($actif === null) {
    echo json_encode(['success' => false, 'message' => 'Erreur technique.']);
    exit;
}

$message = $actif
    ? '<i class="bi bi-heart-fill me-1"></i>Ajouté à votre liste d\'envie.'
    : '<i class="bi bi-heart me-1"></i>Retiré de votre liste d\'envie.';

echo json_encode(['success' => true, 'actif' => $actif, 'message' => $message]);
