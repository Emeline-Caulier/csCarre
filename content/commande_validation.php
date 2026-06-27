<?php
if (!isset($_SESSION['client_id'])) {
    header("Location: index_.php?page=compte.php");
    exit;
}

$idClient = (int)$_SESSION['client_id'];
$panierDAO = new PanierDAO($cnx);
$commandeDAO = new CommandeDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);
$clientDAO = new ClientDAO($cnx);

$client = $clientDAO->getClientById($idClient);
$id_session = session_id();
$id_panier = $panierDAO->getPanier($id_session, $idClient);
$contenu = $id_panier ? $panierDAO->getContenu($id_panier) : [];

if (empty($contenu)) {
    header("Location: index_.php?page=panier.php");
    exit;
}

$promotions = $promotionDAO->getAllIndexedByProduit();

$totalPanier = 0.0;
$lignesRecap = [];
foreach ($contenu as $item) {
    $prixNettoye = (float)str_replace(['€', ' ', ','], ['', '', '.'], (string)$item->prix);
    $prixFinal = isset($promotions[$item->id_produit]) ? (float)$promotions[$item->id_produit]->prix_reduit : $prixNettoye;
    $sousTotal = $prixFinal * (int)$item->quantite;
    $totalPanier += $sousTotal;
    $lignesRecap[] = [
        'nom' => $item->nom_produit,
        'quantite' => (int)$item->quantite,
        'prix' => $prixFinal,
        'sous_total' => $sousTotal,
    ];
}
$fraisLivraison = ($totalPanier >= 50.0) ? 0.0 : 5.0;
$totalFinal = $totalPanier + $fraisLivraison;

$erreurCommande = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'passer_commande') {
    // Vérifie le stock avant validation pour fournir un message précis si insuffisant
    $articlesIndispo = [];
    foreach ($contenu as $item) {
        if ((int)$item->quantite > (int)$item->stock) {
            $articlesIndispo[] = $item->nom_produit . ' (demandé : ' . $item->quantite . ', en stock : ' . $item->stock . ')';
        }
    }
    if (!empty($articlesIndispo)) {
        $erreurCommande = 'Stock insuffisant pour : ' . implode(', ', $articlesIndispo) . '. Veuillez ajuster votre panier.';
    } else {
        $idCommande = $commandeDAO->creerCommande($idClient, $id_panier, $totalFinal);
        if ($idCommande) {
            header("Location: index_.php?page=compte.php&vue=commande&id=$idCommande&success=commande");
            exit;
        }
        $erreurCommande = 'Une erreur est survenue lors de la validation. Veuillez réessayer.';
    }
}
?>

<div class="theme-section">
    <h2 class="theme-section-title">Validation de la commande</h2>
    <div class="p-4">

        <div class="mb-3">
            <a href="index_.php?page=panier.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour au panier
            </a>
        </div>

        <?php if ($erreurCommande !== ''): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($erreurCommande, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Récapitulatif des articles -->
            <div class="col-lg-7">
                <div class="theme-dashboard-bloc">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-bag me-2"></i>Mes articles
                    </h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Prix unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lignesRecap as $l): ?>
                                    <tr>
                                        <td class="fw-medium"><?= htmlspecialchars($l['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-center"><?= $l['quantite'] ?></td>
                                        <td class="text-end"><?= number_format($l['prix'], 2, ',', ' ') ?> €</td>
                                        <td class="text-end fw-semibold"><?= number_format($l['sous_total'], 2, ',', ' ') ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-flex flex-column gap-4">

                <!-- Adresse de livraison -->
                <div class="theme-dashboard-bloc">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-geo-alt me-2"></i>Adresse de livraison
                    </h5>
                    <?php if ($client): ?>
                        <p class="mb-1 fw-semibold">
                            <?= htmlspecialchars($client->prenom_client, ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars($client->nom_client, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p class="text-muted mb-0">
                            <?= htmlspecialchars($client->numero, ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars($client->rue, ENT_QUOTES, 'UTF-8') ?><br>
                            <?= htmlspecialchars($client->code_postal, ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars($client->ville, ENT_QUOTES, 'UTF-8') ?><br>
                            <?= htmlspecialchars($client->pays, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <a href="index_.php?page=compte.php&vue=profil" class="btn btn-link btn-sm p-0 mt-2 text-decoration-none">
                            <i class="bi bi-pencil me-1"></i>Modifier l'adresse
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Total et confirmation -->
                <div class="theme-dashboard-bloc">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-receipt me-2"></i>Récapitulatif
                    </h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span><?= number_format($totalPanier, 2, ',', ' ') ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Livraison</span>
                        <?php if ($fraisLivraison === 0.0): ?>
                            <span class="text-success fw-semibold">Offerte</span>
                        <?php else: ?>
                            <span><?= number_format($fraisLivraison, 2, ',', ' ') ?> €</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 theme-color-rose"><?= number_format($totalFinal, 2, ',', ' ') ?> €</span>
                    </div>

                    <form method="post" action="index_.php?page=commande_validation.php">
                        <input type="hidden" name="action" value="passer_commande">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle me-2"></i>Confirmer la commande
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
