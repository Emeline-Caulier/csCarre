<?php
$nbCommandesAttente = 0;
$nbMessagesNonLus = 0;
$nbAvisEnAttente = 0;

if (isset($_SESSION['admin_id']) && isset($cnx)) {

    $commandeDAO = new CommandeDAO($cnx);
    $nbCommandesAttente = $commandeDAO->countEnAttente();

    $messageDAO = new MessageDAO($cnx);
    $nbMessagesNonLus = $messageDAO->countNonLus();

    $avisDAO = new AvisDAO($cnx);
    $nbAvisEnAttente = $avisDAO->countEnAttente();
}
?>

<?php $pageActive = $_GET['page'] ?? ''; ?>

<nav class="nav-categories">
    <a href="./index_.php?page=accueil.php" class="btn-categorie <?= $pageActive === 'accueil.php' ? 'actif' : '' ?>">Accueil</a>
    <a href="./index_.php?page=accueil_config.php" class="btn-categorie <?= $pageActive === 'accueil_config.php' ? 'actif' : '' ?>">Éditer l'Accueil</a>
    <a href="./index_.php?page=produit.php" class="btn-categorie <?= $pageActive === 'produit.php' ? 'actif' : '' ?>">Inventaire</a>
    <a href="./index_.php?page=client.php" class="btn-categorie <?= $pageActive === 'client.php' ? 'actif' : '' ?>">Clients</a>

    <span class="btn-categorie-wrapper">
        <a href="./index_.php?page=commande.php" class="btn-categorie <?= $pageActive === 'commande.php' ? 'actif' : '' ?>">Commandes</a>
        <?php if ($nbCommandesAttente > 0): ?>
            <span class="nav-badge"><?= $nbCommandesAttente ?></span>
        <?php endif; ?>
    </span>

    <span class="btn-categorie-wrapper">
        <a href="./index_.php?page=message.php" class="btn-categorie <?= $pageActive === 'message.php' ? 'actif' : '' ?>">Messages</a>
        <?php if ($nbMessagesNonLus > 0): ?>
            <span class="nav-badge"><?= $nbMessagesNonLus ?></span>
        <?php endif; ?>
    </span>

    <span class="btn-categorie-wrapper">
        <a href="./index_.php?page=avis.php" class="btn-categorie <?= $pageActive === 'avis.php' ? 'actif' : '' ?>">Avis</a>
        <?php if ($nbAvisEnAttente > 0): ?>
            <span class="nav-badge"><?= $nbAvisEnAttente ?></span>
        <?php endif; ?>
    </span>

</nav>
