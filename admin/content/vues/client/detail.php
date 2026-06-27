<div class="theme-section">
    <h2 class="theme-section-title"><?= $client ? $client->prenom_client . ' ' . $client->nom_client : 'Détail client' ?></h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="mb-4">
        <a href="index_.php?page=client.php" class="btn btn-outline-secondary btn-sm">← Clients</a>
    </div>

    <?php if (!$client): ?>
        <div class="alert alert-danger">Client introuvable.</div>
    <?php else: ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">
                        <i class="bi bi-person me-2"></i>Informations
                    </h6>
                    <p class="fw-bold mb-1"><?= $client->prenom_client ?> <?= $client->nom_client ?></p>
                    <p class="mb-3 text-muted"><?= $client->email_client ?></p>
                    <hr class="my-2">
                    <h6 class="text-muted text-uppercase fw-semibold mb-2 mt-3">
                        <i class="bi bi-geo-alt me-2"></i>Adresse
                    </h6>
                    <?php if ($client->rue): ?>
                        <p class="mb-1"><?= $client->numero ?> <?= $client->rue ?></p>
                        <p class="mb-0"><?= $client->code_postal ?> <?= $client->ville ?> — <?= $client->pays ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0">Aucune adresse enregistrée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">
                        <i class="bi bi-bar-chart me-2"></i>Historique d'achats
                    </h6>
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <p class="fs-3 fw-bold mb-0"><?= $stats['nb_commandes'] ?></p>
                            <p class="text-muted small mb-0">Commande<?= $stats['nb_commandes'] > 1 ? 's' : '' ?></p>
                        </div>
                        <div class="col-4">
                            <p class="fs-3 fw-bold mb-0"><?= number_format((float)$stats['total_depense'], 2, ',', ' ') ?> €</p>
                            <p class="text-muted small mb-0">Total dépensé</p>
                        </div>
                        <div class="col-4">
                            <p class="fs-3 fw-bold mb-0">
                                <?= $stats['derniere_commande'] ? date('d/m/Y', strtotime($stats['derniere_commande'])) : '—' ?>
                            </p>
                            <p class="text-muted small mb-0">Dernière commande</p>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="index_.php?page=commande.php&id_client=<?= $client->id_client ?>"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bag me-1"></i>Voir toutes les commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
    </div>
</div>
