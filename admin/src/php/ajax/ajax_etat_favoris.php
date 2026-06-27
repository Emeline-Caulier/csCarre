<?php
// Renvoie la liste des id_produit en favoris pour la session/client courant.
// Utilisé au chargement de chaque page pour pré-allumer les boutons cœur des
// produits déjà en liste d'envie, sans avoir à recharger la page.
// Réponse JSON : { ids: [int, ...] }.
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../utils/all_includes.php";

header('Content-Type: application/json');

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

$listeEnvieDAO = new ListeEnvieDAO($cnx);
echo json_encode(['ids' => $listeEnvieDAO->getIdsFavoris($id_session, $id_client)]);
