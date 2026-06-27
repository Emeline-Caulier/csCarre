<!-- Formulaire deux-en-un : connexion ou inscription selon le résultat de la vérification AJAX -->
<div class="theme-login-wrapper theme-dashboard-bloc p-4">
    <form method="post" action="index_.php?page=compte.php" id="form-auth-unique">
        <input type="hidden" name="action" value="submit_auth">
        <input type="hidden" id="id_client_cache" name="id_client">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email_client" id="email_client" class="form-control" required
                       pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                       title="Veuillez saisir une adresse email valide (ex : nom@exemple.fr).">
            </div>
            <div class="col-md-6 mb-3">
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required
                       pattern="^(?=.*[a-zA-Z])(?=.*\d).{8,}$"
                       title="Le mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.">
                <div class="form-text text-muted">8 caractères minimum, avec au moins une lettre et un chiffre.</div>
            </div>
        </div>

        <div id="section-complementaire">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label>Prénom</label>
                    <input type="text" name="prenom_client" id="prenom_client" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Nom</label>
                    <input type="text" name="nom_client" id="nom_client" class="form-control" required>
                </div>
            </div>

            <p class="small text-muted"><i class="bi bi-geo-alt"></i> Adresse de livraison</p>
            <div class="row g-2 mb-2">
                <div class="col-8">
                    <input type="text" name="rue" id="rue" class="form-control" placeholder="Rue" required>
                </div>
                <div class="col-4">
                    <input type="text" name="numero" id="numero" class="form-control" placeholder="N°" required>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <input type="text" name="code_postal" id="code_postal" class="form-control" placeholder="Code postal" required>
                </div>
                <div class="col-8">
                    <input type="text" name="ville" id="ville" class="form-control" placeholder="Ville" required>
                </div>
            </div>
            <div class="mb-3">
                <select name="pays" id="pays" class="form-select" required>
                    <option value="">— Pays —</option>
                    <?php foreach ($paysList as $p): ?>
                        <option value="<?= e($p) ?>"><?= e($p) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit" id="submit_client" class="btn btn-primary w-100 mt-3">
            S'inscrire ou se connecter
        </button>
    </form>
</div>
