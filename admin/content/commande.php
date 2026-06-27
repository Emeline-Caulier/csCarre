<?php
// Contrôleur commande : traite les actions POST et prépare les données pour les vues
require "src/php/utils/check_connexion.php";

$commandeDAO = new CommandeDAO($cnx);

// --- Actions POST ---

// Basculer le statut de paiement (Payé / En attente)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_paiement') {
    $idCommande = (int)($_POST['id_commande'] ?? 0);
    if ($idCommande > 0) {
        $commandeDAO->updatePaiement($idCommande, (bool)(int)($_POST['statut_paiement'] ?? 0));
    }
    header("Location: " . buildRedirectUrl('commande.php', [
        'id_client' => !empty($_POST['id_client']) ? (int)$_POST['id_client'] : null,
        'filtre'    => $_POST['filtre'] ?? null,
    ]));
    exit;
}

// Mettre à jour le statut d'avancement de la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_statut') {
    $idCommande = (int)($_POST['id_commande'] ?? 0);
    if ($idCommande > 0) {
        $commandeDAO->updateStatut($idCommande, $_POST['statut_commande'] ?? '');
    }
    header("Location: " . buildRedirectUrl('commande.php', [
        'id_client' => !empty($_POST['id_client']) ? (int)$_POST['id_client'] : null,
        'filtre' => $_POST['filtre'] ?? null,
    ]));
    exit;
}

// --- Préparation des données ---

$idClient = isset($_GET['id_client']) ? (int)$_GET['id_client'] : null;

// Vue détail d'une commande
if (isset($_GET['detail'])) {
    $idDetail = (int)$_GET['detail'];
    $details = $commandeDAO->getCommandeDetails($idDetail);
    $lignes = $commandeDAO->getLignesCommande($idDetail);
    include 'vues/commande/detail.php';
    return;
}

// Liste : toutes les commandes ou celles d'un client spécifique
if ($idClient) {
    $commandes = $commandeDAO->getCommandesByClient($idClient);
    $clientNom = !empty($commandes)
        ? $commandes[0]->prenom_client . ' ' . $commandes[0]->nom_client
        : 'Client #' . $idClient;
} else {
    $commandes = $commandeDAO->getAllCommandes();
    $clientNom = null;
}

// Couleurs des badges Bootstrap selon le statut
$configStatuts = [
    'en_attente' => 'warning',
    'approuvée' => 'success',
    'envoyée' => 'info',
    'annulée' => 'danger',
];

$statutsDisponibles = ['en_attente', 'approuvée', 'envoyée', 'annulée'];

$filtresDisponibles = [
    '' => 'Toutes les commandes',
    'en_attente' => 'En attente',
    'approuvée' => 'Approuvées',
    'envoyée' => 'Envoyées',
    'annulée' => 'Annulées',
];

// Filtre actif validé contre la liste autorisée
$filtre = (isset($_GET['filtre']) && array_key_exists($_GET['filtre'], $filtresDisponibles))
    ? $_GET['filtre'] : '';

// Compteur "en attente" calculé avant filtrage (pour le badge dans l'en-tête)
$enAttenteCount = count(array_filter($commandes, fn($c) => $c->statut_commande === 'en_attente'));

if ($filtre !== '') {
    $commandes = array_values(array_filter($commandes, fn($c) => $c->statut_commande === $filtre));
}

// URL de base pour construire les liens des filtres dans la vue
$lienBase = 'index_.php?page=commande.php' . ($idClient ? '&id_client=' . $idClient : '');

// --- Affichage ---

include 'vues/commande/liste.php';
