<?php
$clientDAO = new ClientDAO($cnx);
$commandeDAO = new CommandeDAO($cnx);
$messageDAO = new MessageDAO($cnx);
$listeEnvieDAO = new ListeEnvieDAO($cnx);
$avisDAO = new AvisDAO($cnx);
$panierDAO = new PanierDAO($cnx);

require 'content/compte_post.php';

// --- Préparation des données ---

$vue = $_GET['vue'] ?? 'dashboard';

$erreur = match($_GET['err'] ?? '') {
    'auth'  => 'Identifiants incorrects ou informations manquantes.',
    'avis_photo_lourde' => 'La photo de votre avis dépasse la limite autorisée de 5 Mo. Merci de la compresser ou d\'en choisir une plus légère.',
    default => '',
};
$succes = match($_GET['success'] ?? '') {
    'profil' => 'Profil mis à jour avec succès.',
    'contact' => 'Message envoyé. Nous vous répondrons dans les plus brefs délais.',
    'commande' => 'Votre commande a bien été enregistrée !',
    'avis' => 'Merci pour votre avis ! Il sera publié après modération.',
    default => '',
};

$client = null;
$commandes = [];
$commande = null;
$lignes = [];
$nbFavoris = 0;
$nbArticlesPanier = 0;
$produitsAEvaluer = [];
$paysList = [
    'Allemagne', 'Autriche', 'Belgique', 'Croatie', 'Danemark', 'Espagne',
    'Finlande', 'France', 'Grèce', 'Hongrie', 'Italie', 'Luxembourg',
    'Pays-Bas', 'Pologne', 'Portugal', 'République Tchèque', 'Roumanie', 'Suède',
];

if (isset($_SESSION['client_id'])) {
    $idClient = (int)$_SESSION['client_id'];
    $idSession = session_id();
    $client = $clientDAO->getClientById($idClient);
    $commandes = $commandeDAO->getCommandesByClient($idClient);
    $nbFavoris = $listeEnvieDAO->count(session_id(), $idClient);
    $produitsAEvaluer = $avisDAO->getProduitsAEvaluer($idClient);
    $id_panier = $panierDAO->getPanier($idSession, $idClient);
    $contenuPanier = $id_panier ? $panierDAO->getContenu($id_panier) : [];
    foreach ($contenuPanier as $item) {
        $nbArticlesPanier += (int)$item->quantite;
    }

    if ($vue === 'commande') {
        $idCommande = (int)($_GET['id'] ?? 0);
        $idsAutorises = array_map(fn($c) => $c->id_commande, $commandes);
        if ($idCommande > 0 && in_array($idCommande, $idsAutorises)) {
            $commande = $commandeDAO->getCommandeDetails($idCommande);
            $lignes = $commandeDAO->getLignesCommande($idCommande);
        } else {
            $vue = 'dashboard';
        }
    }
}

// --- Affichage ---

$vuesDir = 'content/vues/compte/';
?>

<div class="theme-section">
    <h2 class="theme-section-title">Mon compte</h2>

    <div class="p-4">
        <?php if ($succes !== ''): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= $succes ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($erreur !== ''): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i><?= $erreur ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['client_id'])): ?>
            <?php include $vuesDir . 'connexion.php'; ?>
        <?php else: ?>
            <?php match($vue) {
                'profil' => include($vuesDir . 'profil.php'),
                'contact' => include($vuesDir . 'contact.php'),
                'commande' => include($vuesDir . 'commande.php'),
                'commandes' => include($vuesDir . 'commandes.php'),
                default => include($vuesDir . 'dashboard.php'),
            }; ?>
        <?php endif; ?>
    </div>
</div>
