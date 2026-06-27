<!-- Cette vue permet la gestion de l'interfarce d'ajout de produit -->

<div class="theme-section">
    <h2 class="theme-section-title"><?= $vue === 'form_add' ? 'Nouveau produit' : 'Modifier le produit' ?></h2>
    <div class="p-4 theme-dashboard-bloc theme-form-wrapper">
    <div class="mb-4">
        <a href="index_.php?page=produit.php<?= $idCategorie ? '&id_cat='.$idCategorie : '' ?>"
           class="btn btn-outline-secondary btn-sm">← Retour</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form id="form-produit" method="post" action="index_.php?page=produit.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?= $vue === 'form_add' ? 'add' : 'edit' ?>">
                <?php if ($vue === 'form_edit'): ?>
                    <input type="hidden" name="id_produit" value="<?= $produit->id_produit ?>">
                <?php endif; ?>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                        <select name="id_categorie" class="form-select" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id_categorie ?>"
                                    <?= ($produit && $produit->id_categorie === $cat->id_categorie) ? 'selected' : '' ?>
                                    <?= (!$produit && $cat->id_categorie === $idCategorie) ? 'selected' : '' ?>>
                                    <?= $cat->nom_categorie ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nom du produit <span class="text-danger">*</span></label>
                        <input type="text" name="nom_produit" class="form-control" required
                               value="<?= htmlspecialchars($produit?->nom_produit ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($produit?->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Prix (€) <span class="text-danger">*</span></label>
                        <input type="number" name="prix" class="form-control" step="0.01" min="0" required
                               value="<?= htmlspecialchars($produit?->prix ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Stock</label>
                        <input type="number" name="stock" class="form-control" min="0"
                               value="<?= $produit?->stock ?? 0 ?>">
                    </div>

                    <div class="col-md-2 d-flex align-items-end pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="est_nouveau" id="chk-est-nouveau"
                                   <?= ($produit?->est_nouveau ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="chk-est-nouveau">
                                <span class="badge theme-badge-violet ms-1">Nouveauté</span>
                            </label>
                        </div>
                    </div>

                    <?php if ($vue === 'form_edit' && !empty($images)): ?>
                    <div class="col-12">
                        <label class="form-label fw-bold">Photos actuelles</label>
                        <p class="text-muted small mb-2">Glissez-déposez pour réordonner. La première image est l'image principale.</p>
                        <div id="sortable-images" class="d-flex flex-wrap gap-2">
                            <?php foreach ($images as $i => $img): ?>
                            <div class="position-relative apercu-sortable" data-id="<?= $img['id_image'] ?>">
                                <?php if ($i === 0): ?>
                                    <span class="badge bg-primary position-absolute bottom-0 start-0 badge-principale">★ Principale</span>
                                <?php endif; ?>
                                <img src="<?= $img['url_image'] ?>" class="img-apercu" alt="photo">
                                <a href="index_.php?page=produit.php&delete_img=<?= $img['id_image'] ?>&edit=<?= $produit->id_produit ?>&id_cat=<?= $idCategorie ?>"
                                   class="btn btn-danger btn-suppr-img position-absolute top-0 end-0 btn-confirmer-suppression-photo">×</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label class="form-label fw-bold">
                            <?= $vue === 'form_edit' ? 'Ajouter de nouvelles photos' : 'Photos du produit' ?>
                        </label>
                        <?php if ($vue === 'form_edit' && !empty($images)): ?>
                            <p class="text-muted small mb-2">
                                Les nouvelles photos seront ajoutées en fin de liste. Pour qu'une nouvelle photo
                                devienne l'image principale, glissez-la en première position dans
                                « Photos actuelles » après l'enregistrement.
                            </p>
                        <?php else: ?>
                            <p class="text-muted small mb-2">La première photo ajoutée sera l'image principale. Glissez pour réordonner.</p>
                        <?php endif; ?>
                        <div id="zone-photos" class="d-flex flex-wrap gap-2 mb-2"></div>
                        <button type="button" id="btn-ajouter-photo" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter une photo
                        </button>
                        <div class="form-text mt-1">Formats acceptés : JPG, PNG, WEBP, GIF.</div>
                        <div id="inputs-photos-cache" class="d-none"></div>
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <?= $vue === 'form_add' ? 'Ajouter le produit' : 'Enregistrer les modifications' ?>
                    </button>
                    <a href="index_.php?page=produit.php<?= $idCategorie ? '&id_cat='.$idCategorie : '' ?>"
                       class="btn btn-light border">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
