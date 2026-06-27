<?php // Requiert : $produits (array Produit), $promotions (array indexé par id_produit) ?>

<?php if (empty($produits)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-gem fs-1 d-block mb-3"></i>
        <p class="fw-semibold">Aucun produit trouvé.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($produits as $p): ?>
            <?php
            $promo = $promotions[$p->id_produit] ?? null;
            $prixPromo = $promo ? $promo->prix_reduit : null;
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="carte-produit-maquette produit-card w-100">
                    <a href="index_.php?page=produit.php&id=<?= $p->id_produit ?>" class="text-decoration-none">
                        <div class="img-container">
                            <?php if ($p->photo_principale): ?>
                                <img src="admin/<?= e($p->photo_principale) ?>" alt="<?= e($p->nom_produit) ?>">
                            <?php else: ?>
                                <div class="catalogue-img-vide">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            <?php endif; ?>
                            <?php if ($p->est_nouveau): ?>
                                <span class="badge-nouveau">Nouveau</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="carte-produit-corps">
                        <div class="carte-produit-entete">
                            <a href="index_.php?page=produit.php&id=<?= $p->id_produit ?>" class="text-decoration-none">
                                <h5 class="produit-titre"><?= htmlspecialchars($p->nom_produit) ?></h5>
                            </a>
                            <button class="btn-wishlist-icon" data-id="<?= $p->id_produit ?>" aria-label="Ajouter aux favoris"><i class="bi bi-heart"></i></button>
                        </div>
                        <p class="produit-desc"><?= htmlspecialchars($p->nom_categorie) ?></p>
                        <div class="carte-produit-pied">
                            <div>
                                <?php if ($prixPromo !== null): ?>
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <span class="text-muted text-decoration-line-through small">
                                            <?= number_format((float)str_replace(',', '.', (string)$p->prix), 2, ',', ' ') ?> €
                                        </span>
                                        <span class="badge theme-badge-teal theme-badge-sm">
                                            -<?= round((float)$promo->reduction_pourcent) ?>%
                                        </span>
                                    </div>
                                    <span class="prix-maquette">
                                        <?= number_format((float)$prixPromo, 2, ',', ' ') ?> €
                                    </span>
                                <?php else: ?>
                                    <span class="prix-maquette">
                                        <?= number_format((float)str_replace(',', '.', (string)$p->prix), 2, ',', ' ') ?> €
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button class="btn-panier-pink-maquette" data-id="<?= $p->id_produit ?>" aria-label="Ajouter au panier">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
