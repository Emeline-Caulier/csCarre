<div class="theme-section">
    <h2 class="theme-section-title"><?= $produit?->nom_produit ?? 'Détail produit' ?></h2>
    <div class="p-4 theme-dashboard-bloc">

    <div class="mb-4">
        <a href="index_.php?page=produit.php&id_cat=<?= $idCategorie ?>"
           class="btn btn-outline-secondary btn-sm">← Retour</a>
    </div>

    <?php if ($produit): ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-4">

                <!-- Colonne gauche : photo principale + miniatures -->
                <div class="col-md-5">
                    <?php $photoprincipale = $images[0] ?? null; ?>
                    <?php if ($photoprincipale): ?>
                        <img src="<?= $photoprincipale['url_image'] ?>"
                             id="photoPrincipale"
                             class="produit-detail-photo-principale"
                             alt="<?= $produit->nom_produit ?>">
                    <?php else: ?>
                        <div class="produit-detail-photo-vide">
                            <i class="bi bi-image text-muted fs-1"></i>
                            <p class="text-muted small mt-2">Aucune photo</p>
                        </div>
                    <?php endif; ?>

                    <?php if (count($images) > 1): ?>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <?php foreach ($images as $i => $img): ?>
                                <img src="<?= $img['url_image'] ?>"
                                     class="galerie-img <?= $i === 0 ? 'active' : '' ?>"
                                     alt="miniature">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Colonne droite : détails -->
                <div class="col-md-7 d-flex flex-column justify-content-between">
                    <div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <small class="text-muted">Catégorie</small>
                                <p class="fw-bold mb-0"><?= $produit->nom_categorie ?></p>
                            </div>
                            <div class="col-md-5">
                                <small class="text-muted">Prix</small>
                                <p class="fw-bold text-primary fs-5 mb-0"><?= $produit->prix ?> €</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Stock</small>
                            <p class="mb-0">
                                <?php if ($produit->stock === 0): ?>
                                    <span class="badge bg-danger fs-6">Rupture</span>
                                <?php elseif ($produit->stock <= 5): ?>
                                    <span class="badge bg-warning text-dark fs-6"><?= $produit->stock ?> restant(s)</span>
                                <?php else: ?>
                                    <span class="badge bg-success fs-6"><?= $produit->stock ?> en stock</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div>
                            <small class="text-muted">Description</small>
                            <p class="mt-1"><?= nl2br($produit->description) ?></p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="index_.php?page=produit.php&edit=<?= $produit->id_produit ?>&id_cat=<?= $idCategorie ?>"
                           class="btn btn-warning">Modifier</a>
                        <a href="index_.php?page=produit.php&delete=<?= $produit->id_produit ?>&id_cat=<?= $idCategorie ?>"
                           class="btn btn-danger btn-confirmer-suppression">Supprimer</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>
    </div>
</div>

