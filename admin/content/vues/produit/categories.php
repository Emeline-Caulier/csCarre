<div class="theme-section">
    <h2 class="theme-section-title">Inventaire</h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="badge rounded-pill bg-light text-dark border"><?= count($categories) ?> catégorie<?= count($categories) > 1 ? 's' : '' ?></span>
            <?php $totalProduits = array_sum($countBycat); ?>
            <span class="badge rounded-pill bg-light text-dark border"><?= $totalProduits ?> produit<?= $totalProduits > 1 ? 's' : '' ?></span>
        </div>
        <div class="d-flex gap-2">
            <?php
            $filtresStock = [
                '' => 'Tous les stocks',
                'en_stock' => 'En stock',
                'surveiller' => 'Stock à surveiller',
                'rupture' => 'Rupture de stock',
            ];
            ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-1"></i><?= $filtresStock[$filtreStock] ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach ($filtresStock as $val => $label): ?>
                        <li>
                            <a class="dropdown-item <?= $filtreStock === $val ? 'active' : '' ?>"
                               href="index_.php?page=produit.php<?= $val !== '' ? '&stock=' . $val : '' ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNouvelleCategorie">
                <i class="bi bi-folder-plus me-1"></i>Nouvelle catégorie
            </button>
            <a href="index_.php?page=produit.php&add=1" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nouveau produit
            </a>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
            <?php $nbProduits = $countBycat[$cat->id_categorie] ?? 0; ?>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 shadow-sm border-0">
                    <a href="index_.php?page=produit.php&id_cat=<?= $cat->id_categorie ?>" class="text-decoration-none">
                        <div class="card-body text-center p-4 categorie-card">
                            <div class="display-4 mb-3">🏷️</div>
                            <h5 class="fw-bold text-dark"><?= $cat->nom_categorie ?></h5>
                            <p class="text-muted mb-2"><?= $nbProduits ?> produit<?= $nbProduits > 1 ? 's' : '' ?></p>
                            <?php $niveauStock = $stockByCat[$cat->id_categorie] ?? ['rupture' => 0, 'surveiller' => 0, 'en_stock' => 0]; ?>
                            <?php if ($niveauStock['rupture'] > 0): ?>
                                <span class="badge bg-danger"><?= $niveauStock['rupture'] ?> rupture<?= $niveauStock['rupture'] > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                            <?php if ($niveauStock['surveiller'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $niveauStock['surveiller'] ?> à surveiller</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="card-footer bg-white border-top d-flex justify-content-center gap-2 py-2">
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                data-id="<?= $cat->id_categorie ?>"
                                data-nom="<?= $cat->nom_categorie ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalModifierCategorie"
                                title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <?php if ($nbProduits === 0): ?>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-id="<?= $cat->id_categorie ?>"
                                    data-nom="<?= $cat->nom_categorie ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalSupprimerCategorie"
                                    title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php else: ?>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-nom="<?= $cat->nom_categorie ?>"
                                    data-nb="<?= $nbProduits ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCategorieNonSupprimable">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($categories)): ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                Aucune catégorie. Créez-en une pour commencer.
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<!-- Modal de création : formulaire simple (nom) qui POST action=cat_add. -->
<div class="modal fade" id="modalNouvelleCategorie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="bi bi-folder-plus me-2"></i>Nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="index_.php?page=produit.php">
                <input type="hidden" name="action" value="cat_add">
                <div class="modal-body">
                    <label class="form-label fw-semibold">Nom de la catégorie</label>
                    <input type="text" name="nom_categorie" class="form-control" placeholder="Ex : Colliers" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition : renomme une catégorie existante. L'id et le nom courant
     sont injectés en JS au clic sur le bouton "Modifier" de la ligne. POST action=cat_edit. -->
<div class="modal fade" id="modalModifierCategorie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="index_.php?page=produit.php">
                <input type="hidden" name="action" value="cat_edit">
                <input type="hidden" name="id_categorie" id="input-edit-cat-id">
                <div class="modal-body">
                    <label class="form-label fw-semibold">Nom de la catégorie</label>
                    <input type="text" name="nom_categorie" id="input-edit-cat-nom" class="form-control" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'erreur : affiché quand la suppression d'une catégorie est refusée
     parce qu'elle contient encore des produits. Lecture seule, pas d'action. -->
<div class="modal fade" id="modalCategorieNonSupprimable" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-warning"><i class="bi bi-exclamation-circle me-2"></i>Suppression impossible</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-non-supprimable-message"></div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression : action irréversible. Le nom est injecté
     en JS pour rappeler à l'utilisateur ce qu'il s'apprête à supprimer. -->
<div class="modal fade" id="modalSupprimerCategorie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Supprimer la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Supprimer définitivement la catégorie <strong id="modal-cat-nom"></strong> ? Cette action est irréversible.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="link-cat-delete" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Supprimer</a>
            </div>
        </div>
    </div>
</div>
