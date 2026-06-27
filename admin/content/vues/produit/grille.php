<div class="theme-section">
    <h2 class="theme-section-title"><?= $categorieCourante?->nom_categorie ?? 'Produits' ?></h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="index_.php?page=produit.php" class="btn btn-outline-secondary btn-sm">← Retour</a>
            <span class="badge bg-secondary rounded-pill"><?= count($produits) ?> produit<?= count($produits) > 1 ? 's' : '' ?></span>
        </div>
        <div class="d-flex gap-2">
            <?php
            $filtresStock = [
                '' => 'Tous les stocks',
                'en_stock' => 'En stock',
                'surveiller' => 'Stock à surveiller',
                'rupture' => 'Rupture de stock',
            ];
            $urlBaseGrille = 'index_.php?page=produit.php&id_cat=' . $idCategorie;
            ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-1"></i><?= $filtresStock[$filtreStock] ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach ($filtresStock as $val => $label): ?>
                        <li>
                            <a class="dropdown-item <?= $filtreStock === $val ? 'active' : '' ?>"
                               href="<?= $urlBaseGrille ?><?= $val !== '' ? '&stock=' . $val : '' ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="index_.php?page=produit.php&add=1&id_cat=<?= $idCategorie ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nouveau produit
            </a>
        </div>
    </div>

    <?php if (empty($produits)): ?>
    <div class="text-center text-muted py-5">
        <div class="display-4 mb-3">📦</div>
        <p>Aucun produit dans cette catégorie.</p>
        <a href="index_.php?page=produit.php&add=1&id_cat=<?= $idCategorie ?>" class="btn btn-primary">Ajouter le premier produit</a>
    </div>
    <?php else: ?>

    <!-- Bouton bascule vue cartes / vue tableau éditable -->
    <div class="mb-3 d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm active" id="btn-vue-cartes">
            <i class="bi bi-grid me-1"></i>Cartes
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-vue-tableau">
            <i class="bi bi-table me-1"></i>Tableau (édition rapide)
        </button>
    </div>

    <!-- Vue cartes -->
    <div id="vue-cartes" class="row g-4">
        <?php foreach ($produits as $p): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 shadow-sm border-0 produit-card">

                <?php if ($p->photo_principale): ?>
                    <img src="<?= $p->photo_principale ?>" class="card-img-top" alt="<?= $p->nom_produit ?>">
                <?php else: ?>
                    <div class="photo-placeholder">📷</div>
                <?php endif; ?>

                <div class="card-body pb-1">
                    <h6 class="card-title fw-bold mb-1" id="carte-nom_produit-<?= $p->id_produit ?>">
                        <?= $p->nom_produit ?>
                    </h6>
                    <p class="mb-2" id="carte-prix-<?= $p->id_produit ?>">
                        <?php if (isset($promotionsParProduit[$p->id_produit])):
                            $promoInfoPrix = $promotionsParProduit[$p->id_produit];
                        ?>
                            <span class="text-decoration-line-through text-muted small"><?= $p->prix ?> €</span>
                            <span class="text-danger fw-bold ms-1"><?= number_format((float)$promoInfoPrix->prix_reduit, 2, ',', ' ') ?> €</span>
                        <?php else: ?>
                            <span class="text-primary fw-bold"><?= $p->prix ?> €</span>
                        <?php endif; ?>
                    </p>
                    <?php if ($p->stock === 0): ?>
                        <span class="badge bg-danger" id="carte-stock-<?= $p->id_produit ?>">Rupture de stock</span>
                    <?php elseif ($p->stock <= 5): ?>
                        <span class="badge bg-warning text-dark" id="carte-stock-<?= $p->id_produit ?>">Stock faible : <?= $p->stock ?></span>
                    <?php else: ?>
                        <span class="badge bg-success" id="carte-stock-<?= $p->id_produit ?>">En stock : <?= $p->stock ?></span>
                    <?php endif; ?>

                    <div class="mt-2" id="theme-promo-carte-<?= $p->id_produit ?>">
                        <?php if (isset($promotionsParProduit[$p->id_produit])):
                            $promoInfo = $promotionsParProduit[$p->id_produit]; ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger">-<?= round((float)$promoInfo->reduction_pourcent) ?>%</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-retirer-promo"
                                        data-id-promo="<?= $promoInfo->id_promotion ?>"
                                        data-id-produit="<?= $p->id_produit ?>"
                                        data-nom="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>"
                                        data-prix="<?= $p->prix ?>">
                                    <i class="bi bi-x-circle me-1"></i>Retirer la promotion
                                </button>
                            </div>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-success btn-sm w-100 btn-ajouter-promo"
                                    data-id="<?= $p->id_produit ?>"
                                    data-nom="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>">
                                <i class="bi bi-tag me-1"></i>Mettre en promotion
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 d-flex gap-1 pb-3 px-3">
                    <a href="index_.php?page=produit.php&detail=<?= $p->id_produit ?>&id_cat=<?= $idCategorie ?>"
                       class="btn btn-sm btn-outline-info flex-fill">Détail</a>
                    <a href="index_.php?page=produit.php&edit=<?= $p->id_produit ?>&id_cat=<?= $idCategorie ?>"
                       class="btn btn-sm btn-outline-warning flex-fill">Modifier</a>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            data-id="<?= $p->id_produit ?>"
                            data-nom="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>"
                            data-cat="<?= $idCategorie ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#modalSupprimerProduit">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Vue tableau : édition inline via contenteditable + AJAX -->
    <div id="vue-tableau" class="d-none">
        <p class="text-muted small mb-2">
            <i class="bi bi-info-circle me-1"></i>
            Cliquez sur une cellule pour la modifier. La sauvegarde est automatique à la perte de focus.
        </p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Nom du produit</th>
                        <th>Stock</th>
                        <th>Prix</th>
                        <th>Promotion</th>
                        <th>Nouveauté</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produits as $p): ?>
                    <tr>
                        <td contenteditable="false" data-champ="id_produit"
                            id="<?= $p->id_produit ?>"><?= $p->id_produit ?></td>
                        <td contenteditable="true" data-champ="nom_produit"
                            id="<?= $p->id_produit ?>"><?= htmlspecialchars($p->nom_produit) ?></td>
                        <td contenteditable="true" data-champ="stock"
                            id="<?= $p->id_produit ?>"><?= $p->stock ?></td>
                        <td contenteditable="true" data-champ="prix"
                            id="<?= $p->id_produit ?>"><?= $p->prix ?></td>
                        <td id="theme-promo-tableau-<?= $p->id_produit ?>">
                            <?php if (isset($promotionsParProduit[$p->id_produit])):
                                $promoInfo = $promotionsParProduit[$p->id_produit]; ?>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-danger">-<?= round((float)$promoInfo->reduction_pourcent) ?>%</span>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-retirer-promo"
                                            data-id-promo="<?= $promoInfo->id_promotion ?>"
                                            data-id-produit="<?= $p->id_produit ?>"
                                            data-nom="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-success btn-sm btn-ajouter-promo"
                                        data-id="<?= $p->id_produit ?>"
                                        data-nom="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center mb-0">
                                <input class="form-check-input chk-est-nouveau" type="checkbox"
                                       role="switch"
                                       data-id="<?= $p->id_produit ?>"
                                       <?= $p->est_nouveau ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td contenteditable="true" data-champ="description"
                            id="<?= $p->id_produit ?>"><?= htmlspecialchars($p->description) ?></td>
                        <td>
                            <a href="#" class="delete-produit btn btn-sm btn-outline-danger"
                               data-id="<?= $p->id_produit ?>">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>
    </div>
</div>

<!-- Modal d'ajout de promotion : saisie du taux (ex. 0.20 = -20%) et des dates de
     validité pour un produit donné. Le nom et l'id du produit sont injectés en JS au
     clic sur "Mettre en promotion". Soumission via AJAX (btn-confirmer-promo). -->
<div class="modal fade" id="modalAjouterPromo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title theme-color-violet"><i class="bi bi-tag me-2"></i>Mettre en promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Produit : <strong id="modal-promo-nom"></strong></p>
                <input type="hidden" id="modal-promo-id-produit">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Taux de réduction <span class="text-muted fw-normal">(ex : 0.20 pour 20%)</span></label>
                    <input type="number" step="0.01" min="0.01" max="0.99" id="modal-promo-taux" class="form-control" placeholder="0.20">
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Date de début</label>
                        <input type="date" id="modal-promo-debut" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Date de fin</label>
                        <input type="date" id="modal-promo-fin" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success fw-bold" id="btn-confirmer-promo">
                    <i class="bi bi-check-circle me-1"></i>Appliquer la promotion
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression d'un produit : action irréversible,
     le nom est injecté en JS pour rappeler à l'utilisateur ce qu'il s'apprête à supprimer. -->
<div class="modal fade" id="modalSupprimerProduit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Supprimer le produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Supprimer définitivement le produit <strong id="modal-produit-nom"></strong> ? Cette action est irréversible.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="link-produit-delete" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Supprimer</a>
            </div>
        </div>
    </div>
</div>
