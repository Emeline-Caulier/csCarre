<?php
require "src/php/utils/check_connexion.php";
require "src/php/utils/accueil_data.php";
?>

<div class="theme-section">
    <h2 class="theme-section-title">Tableau de bord</h2>

    <div class="p-4">

        <!-- Alertes : badges nécessitant une action -->
        <div class="theme-dashboard-bloc">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-bell me-2"></i>À traiter
            </h6>
            <div class="row g-3">

                <div class="col-sm-6 col-lg-4">
                    <a href="index_.php?page=commande.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 dash-alert-commandes <?= (int)$nbCommandesAttente > 0 ? 'has-items' : '' ?>">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="dash-icon dash-icon-commandes">
                                    <i class="bi bi-hourglass-split fs-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="dash-count"><?= $nbCommandesAttente ?></div>
                                    <div class="text-muted small">Commande<?= (int)$nbCommandesAttente > 1 ? 's' : '' ?> en attente</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <a href="index_.php?page=message.php&filtre=non_lu" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 dash-alert-messages <?= (int)$nbMessagesNonLus > 0 ? 'has-items' : '' ?>">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="dash-icon dash-icon-messages">
                                    <i class="bi bi-envelope fs-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="dash-count"><?= $nbMessagesNonLus ?></div>
                                    <div class="text-muted small">Message<?= (int)$nbMessagesNonLus > 1 ? 's' : '' ?> non lu<?= (int)$nbMessagesNonLus > 1 ? 's' : '' ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <a href="index_.php?page=avis.php&filtre=en_attente" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 dash-alert-avis <?= (int)$nbAvisEnAttente > 0 ? 'has-items' : '' ?>">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="dash-icon dash-icon-avis">
                                    <i class="bi bi-star fs-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="dash-count"><?= $nbAvisEnAttente ?></div>
                                    <div class="text-muted small">Avis en attente de modération</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <!-- STATISTIQUES GÉNÉRALES  -->
        <div class="theme-dashboard-bloc">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-bar-chart me-2"></i>Vue d'ensemble
            </h6>
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <a href="index_.php?page=commande.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100 dash-stat-commandes dash-stat-link">
                            <div class="card-body py-4">
                                <i class="bi bi-bag-check fs-2 mb-2 dash-stat-icon-teal"></i>
                                <div class="dash-big-number"><?= $nbCommandesTotal ?></div>
                                <div class="text-muted small">Commandes au total</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg-3">
                    <a href="index_.php?page=client.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100 dash-stat-clients dash-stat-link">
                            <div class="card-body py-4">
                                <i class="bi bi-people fs-2 mb-2 dash-stat-icon-violet"></i>
                                <div class="dash-big-number"><?= $nbClients ?></div>
                                <div class="text-muted small">Clients inscrits</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg-3">
                    <a href="index_.php?page=produit.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100 dash-stat-produits dash-stat-link">
                            <div class="card-body py-4">
                                <i class="bi bi-gem fs-2 mb-2 dash-stat-icon-jaune"></i>
                                <div class="dash-big-number"><?= $nbProduits ?></div>
                                <div class="text-muted small">Produits en catalogue</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg-3">
                    <a href="index_.php?page=produit.php&stock=rupture" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100 dash-stat-ruptures dash-stat-link <?= (int)$nbRuptures > 0 ? 'has-ruptures' : '' ?>">
                            <div class="card-body py-4">
                                <i class="bi bi-exclamation-triangle fs-2 mb-2 <?= (int)$nbRuptures > 0 ? 'dash-stat-icon-rose' : 'dash-stat-icon-ok' ?>"></i>
                                <div class="dash-big-number"><?= $nbRuptures ?></div>
                                <div class="text-muted small">
                                    Rupture<?= (int)$nbRuptures > 1 ? 's' : '' ?> de stock
                                    <?php if ((int)$nbSurveiller > 0): ?>
                                        <br><span class="text-warning"><?= $nbSurveiller ?> à surveiller</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <!-- CHIFFRE D'AFFAIRES -->
        <div class="theme-dashboard-bloc">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-cash-coin me-2"></i>Chiffre d'affaires
            </h6>
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 dash-ca-mois">
                        <div class="card-body d-flex align-items-center gap-4 py-4">
                            <i class="bi bi-calendar3 fs-1 opacity-75"></i>
                            <div>
                                <div class="dash-ca-label">Ce mois-ci</div>
                                <div class="dash-ca-montant"><?= number_format((float)$caMois, 2, ',', ' ') ?> €</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 dash-ca-total">
                        <div class="card-body d-flex align-items-center gap-4 py-4">
                            <i class="bi bi-trophy fs-1 opacity-75"></i>
                            <div>
                                <div class="dash-ca-label">Total cumulé</div>
                                <div class="dash-ca-montant"><?= number_format((float)$caTotal, 2, ',', ' ') ?> €</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- DERNIÈRES COMMANDES + TOP PRODUITS -->
        <div class="theme-dashboard-bloc">
            <div class="row g-4">

                <!-- Dernières commandes -->
                <div class="col-lg-8">
                    <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                        <i class="bi bi-clock-history me-2"></i>5 dernières commandes
                    </h6>
                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($dernieresCommandes)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-bag fs-3 d-block mb-1"></i>
                                            Aucune commande enregistrée.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($dernieresCommandes as $cmd): ?>
                                        <?php $infoStatut = $libellesStatuts[$cmd['statut_commande']] ?? ['label' => $cmd['statut_commande'], 'class' => 'bg-secondary']; ?>
                                        <tr class="tr-clickable" data-href="index_.php?page=commande.php&detail=<?= $cmd['id_commande'] ?>" tabindex="0" role="link">
                                            <td class="text-center text-muted small">#<?= $cmd['id_commande'] ?></td>
                                            <td class="fw-semibold"><?= $cmd['prenom_client'] . ' ' . $cmd['nom_client'] ?></td>
                                            <td class="text-muted small">
                                                <?= date('d/m/Y', strtotime($cmd['date_commande'])) ?>
                                            </td>
                                            <td class="fw-semibold">
                                                <?= number_format((float)$cmd['total_commande'], 2, ',', ' ') ?> €
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= $infoStatut['class'] ?>"><?= $infoStatut['label'] ?></span>
                                                <?php if ($cmd['statut_paiement'] === 't' || $cmd['statut_paiement'] === true || $cmd['statut_paiement'] === '1'): ?>
                                                    <span class="badge bg-secondary ms-1">Payé</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-transparent text-end border-0 py-2">
                            <a href="index_.php?page=commande.php" class="btn btn-sm btn-outline-primary">
                                Voir toutes les commandes <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Top produits -->
                <div class="col-lg-4">
                    <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                        <i class="bi bi-trophy me-2"></i>
                        Top 3 produits vendus
                    </h6>
                    <div class="card border-0 shadow-sm h-auto">
                        <ul class="list-group list-group-flush">
                        <?php if (empty($topProduits)): ?>
                            <li class="list-group-item text-center text-muted py-4">
                                <i class="bi bi-gem fs-3 d-block mb-1"></i>
                                Aucune vente enregistrée.
                            </li>
                        <?php else: ?>
                            <?php foreach ($topProduits as $i => $p): ?>
                                <a href="index_.php?page=produit.php&detail=<?= $p['id_produit'] ?>&id_cat=<?= $p['id_categorie'] ?>"
                                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 text-decoration-none text-body">
                                    <span class="dash-rank-badge dash-rank-<?= $i + 1 ?>">
                                        <?= $i + 1 ?>
                                    </span>
                                    <?php if (!empty($p['image_url'])): ?>
                                        <img src="<?= $p['image_url'] ?>" alt="<?= $p['nom_produit'] ?>"
                                             class="rounded object-fit-cover shadow-sm flex-shrink-0"
                                             width="48" height="48">
                                    <?php else: ?>
                                        <div class="dash-img-placeholder rounded bg-light d-flex align-items-center justify-content-center text-muted flex-shrink-0">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1 text-truncate">
                                        <div class="fw-semibold text-truncate"><?= $p['nom_produit'] ?></div>
                                        <small class="text-muted"><?= $p['total_vendu'] ?> vendu<?= (int)$p['total_vendu'] > 1 ? 's' : '' ?></small>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted small flex-shrink-0"></i>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </ul>
                        <div class="card-footer bg-transparent text-end border-0 py-2">
                            <a href="index_.php?page=produit.php" class="btn btn-sm btn-outline-primary">
                                Gérer l'inventaire <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
