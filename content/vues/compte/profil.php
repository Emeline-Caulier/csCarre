<!-- Formulaire modification de profil -->
<div class="mb-3">
    <a href="index_.php?page=compte.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="theme-dashboard-bloc">
    <h5 class="fw-bold mb-4 theme-color-violet-bold">
        <i class="bi bi-person-gear me-2"></i>Modifier mon profil
    </h5>

    <form method="post" action="index_.php?page=compte.php">
        <input type="hidden" name="action" value="update_profil">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Prénom</label>
                <input type="text" name="prenom_client" class="form-control"
                       value="<?= e($client->prenom_client) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nom</label>
                <input type="text" name="nom_client" class="form-control"
                       value="<?= e($client->nom_client) ?>" required>
            </div>

            <div class="col-md-8">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email_client" class="form-control"
                       value="<?= e($client->email_client) ?>" required
                       pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                       title="Veuillez saisir une adresse email valide (ex : nom@exemple.fr).">
            </div>

            <div class="col-12"><hr></div>

            <div class="col-12">
                <label class="form-label fw-semibold">
                    <i class="bi bi-lock me-1"></i>Nouveau mot de passe
                    <span class="text-muted fw-normal small">(laisser vide pour ne pas changer)</span>
                </label>
                <input type="password" name="mot_de_passe" class="form-control">
            </div>

            <div class="col-12"><hr></div>

            <div class="col-12">
                <p class="fw-semibold mb-2"><i class="bi bi-geo-alt me-1"></i>Adresse de livraison</p>
            </div>

            <div class="col-8">
                <label class="form-label">Rue</label>
                <input type="text" name="rue" class="form-control" value="<?= e($client->rue) ?>">
            </div>
            <div class="col-4">
                <label class="form-label">N°</label>
                <input type="text" name="numero" class="form-control" value="<?= e($client->numero) ?>">
            </div>

            <div class="col-4">
                <label class="form-label">Code postal</label>
                <input type="text" name="code_postal" class="form-control" value="<?= e($client->code_postal) ?>">
            </div>
            <div class="col-8">
                <label class="form-label">Ville</label>
                <input type="text" name="ville" class="form-control" value="<?= e($client->ville) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Pays</label>
                <select name="pays" class="form-select">
                    <?php foreach ($paysList as $p): ?>
                        <option value="<?= e($p) ?>" <?= $client->pays === $p ? 'selected' : '' ?>>
                            <?= e($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Enregistrer
            </button>
            <a href="index_.php?page=compte.php" class="btn btn-light border">Annuler</a>
        </div>
    </form>
</div>
