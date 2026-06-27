<?php
$panierDAO = new PanierDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

// Récupérer l'ID du panier
$id_panier = $panierDAO->getPanier($id_session, $id_client);

// Si on a un ID valide, on récupère le contenu, sinon on renvoie un tableau vide
$contenuPanier = $id_panier ? $panierDAO->getContenu($id_panier) : [];

// Récupérer les promotions actives
$promotions = $promotionDAO->getAllIndexedByProduit();

$totalPanier = 0.0;
?>

<div class="theme-section">
    <h2 class="theme-section-title"><i class="me-2"></i>Mon Panier</h2>
    <div class="p-4">
        <?php if (empty($contenuPanier)): ?>
            <div class="text-center py-5 theme-dashboard-bloc">
                <i class="bi bi-cart-x fs-1 d-block mb-3 text-muted"></i>
                <p class="fw-semibold text-muted">Votre panier est tristement vide.</p>
                <a href="index_.php?page=catalogue.php" class="btn btn-primary mt-3">Retour à la boutique</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="theme-dashboard-bloc table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th colspan="2">Produit</th>
                                <th class="text-center">Prix unitaire</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($contenuPanier as $item): ?>
                                <?php
                                // Calcul du prix unitaire en tenant compte des promos et du format 'money' de Postgres
                                $prixNettoye = (float)str_replace(['€', ' ', ','], ['', '', '.'], (string)$item->prix);
                                $prixFinal = isset($promotions[$item->id_produit]) ? (float)$promotions[$item->id_produit]->prix_reduit : $prixNettoye;
                                $sousTotal = $prixFinal * (int)$item->quantite;
                                $totalPanier += $sousTotal;
                                ?>
                                <tr data-id-produit="<?= $item->id_produit ?>">
                                    <td class="theme-panier-td-img">
                                        <?php if ($item->image_url): ?>
                                            <img src="admin/<?= htmlspecialchars($item->image_url, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item->nom_produit, ENT_QUOTES, 'UTF-8') ?>" class="img-fluid rounded theme-panier-img">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center theme-vignette-60">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold">
                                        <a href="index_.php?page=produit.php&id=<?= $item->id_produit ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($item->nom_produit, ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?= number_format($prixFinal, 2, ',', ' ') ?> €
                                    </td>
                                    <td class="text-center theme-panier-td-qte">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary btn-qte-moins" type="button" data-id="<?= $item->id_produit ?>">-</button>
                                            <input type="text" class="form-control text-center input-qte" value="<?= $item->quantite ?>" data-stock="<?= (int)$item->stock ?>" readonly>
                                            <button class="btn btn-outline-secondary btn-qte-plus" type="button" data-id="<?= $item->id_produit ?>" <?= ((int)$item->quantite >= (int)$item->stock) ? 'disabled' : '' ?>>+</button>
                                        </div>
                                        <?php if ((int)$item->quantite >= (int)$item->stock): ?>
                                            <small class="text-danger d-block mt-1">Stock max atteint</small>
                                        <?php elseif ((int)$item->stock <= 5): ?>
                                            <small class="text-muted d-block mt-1">Stock : <?= (int)$item->stock ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold">
                                        <?= number_format($sousTotal, 2, ',', ' ') ?> €
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger btn-supprimer-panier" data-id="<?= $item->id_produit ?>">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="theme-dashboard-bloc">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Récapitulatif</h5>

                        <?php
                        // Logique de calcul des frais de port
                        $fraisLivraison = ($totalPanier >= 50.0) ? 0.0 : 5.0;
                        $totalFinal = $totalPanier + $fraisLivraison;
                        ?>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?= number_format($totalPanier, 2, ',', ' ') ?> €</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Livraison</span>

                            <?php if ($fraisLivraison === 0.0): ?>
                                <span class="text-success fw-semibold">Offerte</span>
                            <?php else: ?>
                                <div class="text-end">
                                    <span><?= number_format($fraisLivraison, 2, ',', ' ') ?> €</span><br>
                                    <span class="text-muted theme-text-xs">
                                        (Plus que <?= number_format(50 - $totalPanier, 2, ',', ' ') ?> € pour l'avoir offerte !)
                                    </span>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 theme-color-rose"><?= number_format($totalFinal, 2, ',', ' ') ?> €</span>
                        </div>

                        <a href="index_.php?page=commande_validation.php" class="btn btn-primary w-100 py-2">
                            Valider la commande <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
        <?php endif; ?>
    </div>
</div>