<?php
// Instanciation de nos DAO
$configDAO = new ConfigurationDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);
$produitDAO = new ProduitDAO($cnx);

$message = '';

// --- 1. TRAITEMENT DES CONFIGURATIONS (Bannière & À propos) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action_promo'])) {

    // Si on a soumis le formulaire de la bannière
    if (isset($_POST['form_banniere'])) {
        $configDAO->update('accueil_banniere_bouton', trim($_POST['accueil_banniere_bouton']));
    }

    // Si on a soumis le formulaire de la section À propos
    if (isset($_POST['form_apropos'])) {
        $configDAO->update('accueil_apropos_texte', trim($_POST['accueil_apropos_texte']));
        $configDAO->update('accueil_apropos_bouton', trim($_POST['accueil_apropos_bouton']));
    }

    // Traitement des images (validation extension + MIME réel + renommage aléatoire)
    $dossierUpload = 'assets/images/';
    $erreursUpload = [];

    if (!empty($_FILES['accueil_banniere_img']['name'])) {
        $nomFichier = uploadImageSecurisee($_FILES['accueil_banniere_img'], $dossierUpload, 'hero_');
        if ($nomFichier !== null) {
            $configDAO->update('accueil_banniere_img', $dossierUpload . $nomFichier);
        } else {
            $erreursUpload[] = 'Bannière : fichier invalide (formats acceptés : jpg, png, webp, gif — taille max 5 Mo).';
        }
    }

    if (!empty($_FILES['accueil_apropos_img']['name'])) {
        $nomFichier = uploadImageSecurisee($_FILES['accueil_apropos_img'], $dossierUpload, 'apropos_');
        if ($nomFichier !== null) {
            $configDAO->update('accueil_apropos_img', $dossierUpload . $nomFichier);
        } else {
            $erreursUpload[] = 'À propos : fichier invalide (formats acceptés : jpg, png, webp, gif — taille max 5 Mo).';
        }
    }

    if (!empty($erreursUpload)) {
        $message = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>' .
            implode('<br>', array_map(fn($e) => htmlspecialchars($e, ENT_QUOTES, 'UTF-8'), $erreursUpload)) .
            '</div>';
    } else {
        $message = '<div class="alert alert-success">Modifications de la page enregistrées avec succès !</div>';
    }
}

// --- 2. TRAITEMENT DES PROMOTIONS (Carrousel) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_promo'])) {

    if ($_POST['action_promo'] === 'ajouter') {
        $id_produit = (int) $_POST['id_produit'];
        $taux = (float) $_POST['taux_reduction'];
        $debut = $_POST['date_debut'];
        $fin = $_POST['date_fin'];

        // Utilisation du DAO pour vérifier le doublon
        if ($promotionDAO->existeDeja($id_produit)) {
            $message = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Erreur : Ce produit est déjà en promotion dans la vitrine !</div>';
        } else {
            // Utilisation du DAO pour insérer
            $promotionDAO->insert($id_produit, $taux, $debut, $fin);
            $message = '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Promotion ajoutée à la vitrine !</div>';
        }

    } elseif ($_POST['action_promo'] === 'supprimer') {
        $id_promo = (int) $_POST['id_promotion'];
        // Utilisation du DAO pour supprimer
        $promotionDAO->delete($id_promo);
        $message = '<div class="alert alert-warning"><i class="bi bi-info-circle me-2"></i>Promotion retirée de la vitrine.</div>';
    }
}

// --- RÉCUPÉRATION DES DONNÉES POUR L'AFFICHAGE ---

// 1. Configurations de la page
$objetsConfig = $configDAO->getAll();
$config = [];
foreach ($objetsConfig as $c) {
    $config[$c->cle] = $c->valeur;
}

// 2. Liste des produits (via le DAO)
$produits = $produitDAO->getAllForDropdown();

// 3. Liste des promotions actuelles (via le DAO)
$promotions = $promotionDAO->getAllWithDetails();

// 4. Produits de la nouvelle collection
$produitsNouveaux = $produitDAO->getNouvelleCollection();

?>

<div class="theme-section">
    <h2 class="theme-section-title">Personnaliser l'Accueil</h2>

    <div class="p-4 theme-dashboard-bloc">
        <?= $message ?>

        <!-- BLOC 1 : BANNIÈRE -->
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
            <form method="post" action="index_.php?page=accueil_config.php" enctype="multipart/form-data">
                <input type="hidden" name="form_banniere" value="1">
                <h5 class="fw-bold theme-color-violet mb-4"><i class="bi bi-image me-2"></i>Bannière</h5>
                <div class="row g-4 align-items-center">
                    <div class="col-md-5 text-center">
                        <?php if (!empty($config['accueil_banniere_img'])): ?>
                            <img src="<?= $config['accueil_banniere_img'] ?>" alt="Aperçu Bannière" class="img-thumbnail w-100 object-fit-cover shadow-sm">
                        <?php else: ?>
                            <div class="p-5 bg-light border rounded text-muted h-100 d-flex align-items-center justify-content-center">Aucune image de fond</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7 d-flex flex-column justify-content-center">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted mb-2">Modifier l'image de fond</label>
                                <input type="file" name="accueil_banniere_img" class="form-control form-control-lg" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted mb-2">Texte du bouton</label>
                                <input type="text" name="accueil_banniere_bouton" class="form-control form-control-lg" value="<?= $config['accueil_banniere_bouton'] ?? 'Découvrir la nouvelle collection' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm"><i class="bi bi-save me-2"></i>Enregistrer la bannière</button>
                </div>
            </form>
        </div>
        <!-- BLOC 2 : NOUVEAUTÉ -->
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
            <h5 class="fw-bold theme-color-violet mb-4"><i class="bi bi-stars me-2"></i>Nouveauté</h5>

            <!-- Titre de la nouveauté -->
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted mb-2">Titre de la nouveauté</label>
                <input type="text" id="nc-nom-input" class="form-control"
                       value="<?= htmlspecialchars($config['nom_nouvelle_collection'] ?? 'Nouvelle Collection') ?>"
                       placeholder="Ex: Collection Printemps 2025">
            </div>

            <!-- Ajout d'un produit via dropdown -->
            <?php
            $idsDejaPresents = array_map(fn($p) => $p->id_produit, $produitsNouveaux);
            $produitsDisponibles = array_filter($produits, fn($p) => !in_array($p->id_produit, $idsDejaPresents));
            ?>
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted mb-2">Ajouter un produit</label>
                <div class="d-flex gap-2 align-items-center theme-input-compact">
                    <select id="nc-select-produit" class="form-select">
                        <option value="">Choisir un produit…</option>
                        <?php foreach ($produitsDisponibles as $p): ?>
                            <option value="<?= $p->id_produit ?>"><?= htmlspecialchars($p->nom_produit) ?> — <?= $p->prix ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" id="btn-ajouter-nc" class="btn btn-success btn-sm text-nowrap">
                        <i class="bi bi-plus-circle me-1"></i>Ajouter
                    </button>
                </div>
            </div>

            <!-- Tableau des produits dans la collection -->
            <h6 class="fw-bold text-muted mb-3">Produits dans la collection :</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-nouvelle-collection">
                    <?php if (empty($produitsNouveaux)): ?>
                        <tr id="nc-ligne-vide">
                            <td colspan="4" class="text-center text-muted py-4">Aucun produit dans la collection pour le moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produitsNouveaux as $p): ?>
                            <tr id="nc-ligne-<?= $p->id_produit ?>">
                                <td>
                                    <?php if ($p->photo_principale): ?>
                                        <img src="<?= htmlspecialchars($p->photo_principale) ?>" alt="" class="rounded object-fit-cover shadow-sm" width="50" height="50">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted dash-img-placeholder"><i class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= htmlspecialchars($p->nom_produit) ?></td>
                                <td class="text-muted"><?= number_format((float)$p->prix, 2, ',', ' ') ?> €</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-retirer-nc" data-id="<?= $p->id_produit ?>">
                                        <i class="bi bi-trash3"></i> Retirer
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BLOC 3 : VITRINE DES PROMOTIONS -->
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
            <h5 class="fw-bold theme-color-violet mb-4"><i class="bi bi-star me-2"></i>Promotions en vitrine</h5>

            <!-- Formulaire pour AJOUTER une promotion -->
            <form id="form-ajout-promo" method="post" action="index_.php?page=accueil_config.php" class="row g-3 align-items-end mb-4 border-bottom pb-4">
                <input type="hidden" name="action_promo" value="ajouter">

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted mb-2">Produit</label>
                    <select name="id_produit" class="form-select" required>
                        <option value="">Choisir...</option>
                        <?php foreach($produits as $p): ?>
                            <option value="<?= $p->id_produit ?>"><?= $p->nom_produit ?> (<?= $p->prix ?> €)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted mb-2">Taux (ex: 0.20)</label>
                    <input type="number" step="0.01" min="0" max="0.99" name="taux_reduction" class="form-control" placeholder="0.20" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted mb-2">Début</label>
                    <input type="date" name="date_debut" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted mb-2">Fin</label>
                    <input type="date" name="date_fin" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm"><i class="bi bi-plus-circle me-2"></i>Ajouter</button>
                </div>
            </form>

            <!-- Liste pour GÉRER les promotions existantes -->
            <h6 class="fw-bold text-muted mb-3">Produits dans le carrousel :</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix initial</th>
                        <th>Promo</th>
                        <th>Nouveau Prix</th>
                        <th>Validité</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-promotions">
                    <?php if(empty($promotions)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucune promotion en vitrine pour le moment.</td></tr>
                    <?php else: ?>
                        <?php foreach($promotions as $promo):
                            // Le prix Postgres "money" peut arriver formaté ("12,50 €") selon la locale
                            $prixNettoye = str_replace(',', '.', $promo->prix);
                            $prixFloat = (float) preg_replace('/[^0-9.]/', '', $prixNettoye);
                            $prixPromo = $prixFloat * (1 - $promo->taux_reduction);
                            ?>
                            <tr>
                                <td>
                                    <?php if($promo->image_url): ?>
                                        <img src="<?= $promo->image_url ?>" alt="Produit" class="rounded object-fit-cover shadow-sm" width="50" height="50">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted theme-vignette-50"><i class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= $promo->nom_produit ?></td>
                                <td class="text-decoration-line-through text-muted"><?= number_format($prixFloat, 2, ',', ' ') ?> €</td>
                                <td class="theme-inline-promo" data-champ="taux_reduction" data-id="<?= $promo->id_promotion ?>" data-prix="<?= $prixFloat ?>" data-valeur="<?= $promo->taux_reduction ?>" title="Cliquer pour modifier">
                                    <span class="badge bg-danger">-<?= $promo->taux_reduction * 100 ?>%</span>
                                </td>
                                <td class="text-danger fw-bold" id="theme-promo-prix-<?= $promo->id_promotion ?>"><?= number_format($prixPromo, 2, ',', ' ') ?> €</td>
                                <td class="text-muted small">
                                    <span class="theme-inline-promo d-block" data-champ="date_debut" data-id="<?= $promo->id_promotion ?>" data-valeur="<?= $promo->date_debut ?>" title="Cliquer pour modifier">Du <?= date('d/m/Y', strtotime($promo->date_debut)) ?></span>
                                    <span class="theme-inline-promo d-block" data-champ="date_fin" data-id="<?= $promo->id_promotion ?>" data-valeur="<?= $promo->date_fin ?>" title="Cliquer pour modifier">au <?= date('d/m/Y', strtotime($promo->date_fin)) ?></span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-supprimer-promo" data-id="<?= $promo->id_promotion ?>"><i class="bi bi-trash3"></i> Retirer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- BLOC 3 : À PROPOS -->
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
            <form method="post" action="index_.php?page=accueil_config.php" enctype="multipart/form-data">
                <input type="hidden" name="form_apropos" value="1">
                <h5 class="fw-bold theme-color-violet mb-4"><i class="bi bi-info-circle me-2"></i>Section À propos</h5>
                <div class="row g-4 align-items-center">
                    <div class="col-md-5 text-center">
                        <?php if (!empty($config['accueil_apropos_img'])): ?>
                            <img src="<?= $config['accueil_apropos_img'] ?>" alt="Aperçu À propos" class="img-thumbnail w-100 object-fit-cover shadow-sm">
                        <?php else: ?>
                            <div class="p-5 bg-light border rounded text-muted h-100 d-flex align-items-center justify-content-center">Aucune image illustrative</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7 d-flex flex-column justify-content-center">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted mb-2">Modifier l'image illustrative</label>
                                <input type="file" name="accueil_apropos_img" class="form-control form-control-lg" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted mb-2">Texte de présentation</label>
                                <textarea name="accueil_apropos_texte" class="form-control form-control-lg" rows="8"><?= $config['accueil_apropos_texte'] ?? '' ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted mb-2">Texte du bouton</label>
                                <input type="text" name="accueil_apropos_bouton" class="form-control form-control-lg" value="<?= $config['accueil_apropos_bouton'] ?? 'En savoir plus' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm"><i class="bi bi-save me-2"></i>Enregistrer la section</button>
                </div>
            </form>
        </div>



    </div>
</div>