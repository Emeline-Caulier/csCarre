<?php
// Contrôleur client : route vers la vue détail ou la liste
require "src/php/utils/check_connexion.php";

$clientDAO = new ClientDAO($cnx);

// --- Routage des vues ---

// Vue détail d'un client
if (isset($_GET['detail'])) {
    $idDetail = (int)$_GET['detail'];
    $client = $clientDAO->getClientById($idDetail);
    $stats = $clientDAO->getStatsClient($idDetail);
    include 'vues/client/detail.php';
    return;
}

// Vue liste de tous les clients
$clients = $clientDAO->getAllClients();
include 'vues/client/liste.php';
