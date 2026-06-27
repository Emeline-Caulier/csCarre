<?php
// Instanciation des DAO nécessaires au dashboard
$commandeDAO = new CommandeDAO($cnx);
$messageDAO = new MessageDAO($cnx);
$avisDAO = new AvisDAO($cnx);
$clientDAO = new ClientDAO($cnx);
$produitDAO = new ProduitDAO($cnx);

// Indicateurs Clés de Performance
$nbCommandesAttente = $commandeDAO->countEnAttente();
$nbCommandesTotal = $commandeDAO->countTotal();
$nbMessagesNonLus = $messageDAO->countNonLus();
$nbAvisEnAttente = $avisDAO->countEnAttente();
$nbClients = $clientDAO->countTotal();
$nbProduits = $produitDAO->countTotal();
$nbRuptures = $produitDAO->countRuptures();
$nbSurveiller = $produitDAO->countASurveiller();

// Chiffre d'affaires
$caTotal = $commandeDAO->getCATotal();
$caMois = $commandeDAO->getCaMois();

// Listes
$dernieresCommandes = $commandeDAO->getDernieres(5);
$topProduits = $produitDAO->getTopVentes(3);

// Libellés statuts commande
$libellesStatuts = [
    'en_attente' => ['label' => 'En attente', 'class' => 'bg-warning text-dark'],
    'approuvée' => ['label' => 'Approuvée', 'class' => 'bg-success'],
    'envoyée' => ['label' => 'Envoyée', 'class' => 'bg-info'],
    'annulée' => ['label' => 'Annulée', 'class' => 'bg-danger'],
];
