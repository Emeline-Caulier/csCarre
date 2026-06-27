<!-- Formulaire de contact -->
<div class="mb-3">
    <a href="index_.php?page=compte.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="theme-dashboard-bloc">
    <h5 class="fw-bold mb-4 theme-color-violet-bold">
        <i class="bi bi-envelope me-2"></i>Nous contacter
    </h5>

    <form method="post" action="index_.php?page=compte.php">
        <input type="hidden" name="action" value="contact">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Commande concernée
                    <span class="text-muted fw-normal small">(facultatif)</span>
                </label>
                <select name="num_commande" class="form-select">
                    <option value="">— Aucune commande spécifique —</option>
                    <?php foreach ($commandes as $cmd): ?>
                        <option value="<?= (int)$cmd->id_commande ?>">
                            #<?= (int)$cmd->id_commande ?> — <?= date('d/m/Y', strtotime($cmd->date_commande)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                <input type="text" name="sujet" class="form-control" required
                       placeholder="Ex : question sur ma commande, retour produit…">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                <textarea name="contenu" class="form-control" rows="5" required
                          placeholder="Décrivez votre demande…"></textarea>
            </div>

        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send me-1"></i>Envoyer
            </button>
            <a href="index_.php?page=compte.php" class="btn btn-light border">Annuler</a>
        </div>
    </form>
</div>
