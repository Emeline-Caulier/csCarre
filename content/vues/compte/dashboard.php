<?php
if ($client === null) {
    session_destroy();
    header("Location: index_.php?page=compte.php");
exit;
}

$etapesIcones = [
['key' => 'en_attente', 'label' => 'Commande', 'icon' => 'bi-receipt'],
['key' => 'approuvée', 'label' => 'Approuvée', 'icon' => 'bi-box-seam'],
['key' => 'envoyée', 'label' => 'Expédiée', 'icon' => 'bi-truck'],
];

$statutConfig = [
'en_attente' => ['label' => 'En attente', 'badge' => 'bg-warning text-dark', 'icon' => 'bi-clock', 'txt' => 'text-warning'],
'approuvée' => ['label' => 'Approuvée', 'badge' => 'bg-info text-dark', 'icon' => 'bi-box-seam', 'txt' => 'text-info'],
'envoyée' => ['label' => 'Expédiée', 'badge' => 'bg-primary', 'icon' => 'bi-truck', 'txt' => 'text-primary'],
'annulée' => ['label' => 'Annulée', 'badge' => 'bg-danger', 'icon' => 'bi-x-circle', 'txt' => 'text-danger'],
];

$statutsActifs = ['en_attente', 'approuvée'];
$commandesActives = array_values(array_filter($commandes, fn($c) => in_array($c->statut_commande, $statutsActifs)));
$commandesHistorique = array_values(array_filter($commandes, fn($c) => !in_array($c->statut_commande, $statutsActifs)));

$adresseLigne1 = trim($client->numero . ' ' . $client->rue);
$adresseLigne2 = trim($client->code_postal . ' ' . $client->ville);
?>

    <!-- BIENVENUE -->
    <div class="theme-dashboard-bloc mb-4">
        <h3 class="fw-bold mb-0 theme-color-violet">Bonjour, <?= e($client->prenom_client) ?> 👋</h3>
        <p class="text-muted small mb-0">Bienvenue dans votre espace client Acrylia</p>
    </div>

    <!-- BLOC 1 : Mon compte | Nous contacter | Se déconnecter -->
    <div class="theme-dashboard-bloc">
        <div class="row g-3">

            <!-- Mon compte -->
            <div class="col-md-4">
                <div class="theme-bloc-card">
                    <div class="theme-bloc-card-title">
                        <i class="bi bi-person-gear"></i> Mon compte
                    </div>
                    <div class="flex-grow-1">
                        <p class="fw-bold mb-1"><?= e($client->prenom_client) ?> <?= e($client->nom_client) ?></p>
                        <p class="text-muted small mb-2"><?= e($client->email_client) ?></p>

                        <?php if ($adresseLigne1 || $adresseLigne2): ?>
                            <p class="small mb-0">
                                <i class="bi bi-geo-alt me-1 text-muted"></i>
                                <?= e($adresseLigne1) ?><br>
                                <span class="ms-3"><?= e($adresseLigne2) ?></span>
                                <?php if ($client->pays): ?>
                                    <br><span class="ms-3"><?= e($client->pays) ?></span>
                                <?php endif; ?>
                            </p>
                        <?php else: ?>
                            <p class="small text-muted fst-italic mb-0">Aucune adresse renseignée</p>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <a href="index_.php?page=compte.php&vue=profil" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-pencil me-1"></i>Modifier mes informations
                        </a>
                    </div>
                </div>
            </div>

            <!-- Nous contacter -->
            <div class="col-md-4">
                <div class="theme-bloc-card">
                    <div class="theme-bloc-card-title">
                        <i class="bi bi-envelope-heart"></i> Nous contacter
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-0">
                            Une question sur votre commande, un retour produit ou simplement envie d'échanger ?
                            Notre équipe vous répond dans les plus brefs délais.
                        </p>
                    </div>
                    <div class="mt-3">
                        <a href="index_.php?page=compte.php&vue=contact" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-send me-1"></i>Envoyer un message
                        </a>
                    </div>
                </div>
            </div>

            <!-- Se déconnecter -->
            <div class="col-md-4">
                <div class="theme-bloc-card theme-bloc-card--danger">
                    <div class="theme-bloc-card-title theme-bloc-card-title--danger">
                        <i class="bi bi-box-arrow-right"></i> Se déconnecter
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-0">Fermer votre session en toute sécurité depuis cet appareil.</p>
                    </div>
                    <div class="mt-3">
                        <a href="index_.php?page=disconnect.php" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-box-arrow-right me-1"></i>Se déconnecter
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- BLOC 2 : Mon panier | Ma liste d'envie -->
    <div class="theme-dashboard-bloc">
        <div class="row g-3">

            <!-- Mon panier -->
            <div class="col-md-6">
                <div class="theme-bloc-card">
                    <div class="theme-bloc-card-title">
                        <i class="bi bi-bag"></i> Mon panier
                    </div>
                    <div class="flex-grow-1 text-center py-2">
                        <?php if ($nbArticlesPanier > 0): ?>
                            <span class="fs-3 fw-bold theme-color-rose"><?= $nbArticlesPanier ?></span>
                            <p class="text-muted small mb-0">article<?= $nbArticlesPanier > 1 ? 's' : '' ?> dans votre panier</p>
                        <?php else: ?>
                            <i class="bi bi-bag-x fs-2 d-block mb-2 theme-empty-icon"></i>
                            <p class="text-muted small mb-0">Votre panier est vide</p>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <a href="index_.php?page=panier.php" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-bag me-1"></i>Voir le panier
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ma liste d'envie -->
            <div class="col-md-6">
                <div class="theme-bloc-card">
                    <div class="theme-bloc-card-title">
                        <i class="bi bi-heart"></i> Ma liste d'envie
                    </div>
                    <div class="flex-grow-1 text-center py-2">
                        <?php if ($nbFavoris > 0): ?>
                            <span class="fs-3 fw-bold theme-color-rose"><?= $nbFavoris ?></span>
                            <p class="text-muted small mb-0">article<?= $nbFavoris > 1 ? 's' : '' ?> dans votre liste</p>
                        <?php else: ?>
                            <i class="bi bi-heart-slash fs-2 d-block mb-2 theme-empty-icon"></i>
                            <p class="text-muted small mb-0">Votre liste d'envie est vide</p>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <a href="index_.php?page=favoris.php" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-heart me-1"></i>Voir la liste d'envie
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- BLOC : AVIS À DONNER (uniquement si commandes envoyées non encore évaluées) -->
<?php if (!empty($produitsAEvaluer)): ?>
    <div class="theme-dashboard-bloc">
        <h5 class="fw-bold mb-2 theme-color-violet">
            <i class="bi bi-star me-2"></i>Donner mon avis
        </h5>
        <p class="text-muted small mb-4">
            Vous avez reçu <?= count($produitsAEvaluer) ?> produit<?= count($produitsAEvaluer) > 1 ? 's' : '' ?> qui n'<?= count($produitsAEvaluer) > 1 ? 'ont' : 'a' ?> pas encore été évalué<?= count($produitsAEvaluer) > 1 ? 's' : '' ?>. Partagez votre expérience !
        </p>
        <div class="row g-3">
            <?php foreach ($produitsAEvaluer as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="theme-bloc-card">
                        <div class="d-flex gap-3 align-items-center mb-3">
                            <?php if (!empty($p->url_image)): ?>
                                <img src="admin/<?= htmlspecialchars($p->url_image, ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>"
                                     class="theme-panier-img rounded">
                            <?php else: ?>
                                <div class="theme-panier-img rounded bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <p class="fw-bold mb-0"><?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="text-muted small mb-0">
                                    Commande #<?= (int)$p->id_commande ?>
                                    — <?= date('d/m/Y', strtotime($p->date_commande)) ?>
                                </p>
                            </div>
                        </div>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm w-100 mt-auto btn-ouvrir-avis"
                                data-id-commande="<?= (int)$p->id_commande ?>"
                                data-id-produit="<?= (int)$p->id_produit ?>"
                                data-nom-produit="<?= htmlspecialchars($p->nom_produit, ENT_QUOTES, 'UTF-8') ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAjoutAvis">
                            <i class="bi bi-star me-1"></i>Donner mon avis
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal de soumission d'avis : formulaire (note, commentaire, photo facultative)
         pour évaluer un produit d'une commande expédiée. Mutualisé pour tous les produits :
         id_commande et id_produit sont injectés en JS au clic sur "Donner mon avis". -->
    <div class="modal fade" id="modalAjoutAvis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="index_.php?page=compte.php" enctype="multipart/form-data" class="modal-content" id="form-avis">
                <input type="hidden" name="action" value="submit_avis">
                <input type="hidden" name="id_commande" id="avis-id-commande" value="">
                <input type="hidden" name="id_produit" id="avis-id-produit" value="">
                <input type="hidden" name="note_etoiles" id="avis-note" value="">

                <div class="modal-header border-0">
                    <h5 class="modal-title theme-color-violet">
                        <i class="bi bi-star me-2"></i>
                        Mon avis sur <span id="avis-nom-produit" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <!-- Étoiles cliquables : icônes vides au départ, JS gère le remplissage -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">
                            Note <span class="text-muted small">(cliquez sur les étoiles)</span>
                        </label>
                        <div class="theme-rating-input" role="radiogroup" aria-label="Note">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star theme-star" data-value="<?= $i ?>"
                                   role="button" tabindex="0"
                                   aria-label="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="avis-commentaire" class="form-label fw-semibold">Votre commentaire</label>
                        <textarea class="form-control" id="avis-commentaire" name="commentaire"
                                  rows="4" maxlength="1000" required
                                  placeholder="Partagez votre expérience avec ce produit..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="avis-photo" class="form-label fw-semibold">
                            Photo <span class="text-muted small">(facultative, max 5 Mo)</span>
                        </label>
                        <input class="form-control" type="file" id="avis-photo" name="photo_avis"
                               accept="image/jpeg,image/png,image/webp,image/gif">
                    </div>

                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Votre avis sera publié après validation par notre équipe.
                    </p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Envoyer mon avis
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

    <!-- BLOC 3 : COMMANDES EN COURS -->
<?php if (!empty($commandesActives)): ?>
    <div class="theme-dashboard-bloc">
        <h5 class="fw-bold mb-4 theme-color-violet">
            <i class="bi bi-box-seam me-2"></i>Commande<?= count($commandesActives) > 1 ? 's' : '' ?> en cours
        </h5>
        <?php foreach ($commandesActives as $cmd): ?>
            <?php include __DIR__ . '/carte_commande.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <!-- BLOC 4 : HISTORIQUE -->
<?php if (!empty($commandesHistorique)): ?>
    <div class="theme-dashboard-bloc">
        <h5 class="fw-bold mb-4 theme-color-violet">
            <i class="bi bi-clock-history me-2"></i>Historique des commandes
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (array_slice($commandesHistorique, 0, 3) as $cmd):
                    $sc = $statutConfig[$cmd->statut_commande] ?? ['label' => $cmd->statut_commande, 'badge' => 'bg-secondary'];
                    ?>
                    <tr>
                        <td class="fw-semibold">#<?= $cmd->id_commande ?></td>
                        <td><?= date('d/m/Y', strtotime($cmd->date_commande)) ?></td>
                        <td class="fw-bold theme-color-rose">
                            <?= number_format((float)$cmd->total_commande, 2, ',', ' ') ?> €
                        </td>
                        <td>
                            <span class="badge <?= $sc['badge'] ?>"><?= $sc['label'] ?></span>
                        </td>
                        <td>
                            <a href="index_.php?page=compte.php&vue=commande&id=<?= $cmd->id_commande ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i>Détail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="index_.php?page=compte.php&vue=commandes" class="btn btn-outline-secondary">
                <i class="bi bi-list-ul me-1"></i>Voir toutes mes commandes
            </a>
        </div>
    </div>

<?php elseif (empty($commandes)): ?>
    <div class="theme-dashboard-bloc text-center py-5">
        <i class="bi bi-bag-x fs-1 d-block mb-3 theme-empty-icon-lg"></i>
        <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
        <a href="index_.php?page=accueil.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-right me-1"></i>Découvrir la boutique
        </a>
    </div>
<?php endif; ?>