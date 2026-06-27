<div class="theme-section">
    <h2 class="theme-section-title">
        Commande #<?= $details ? str_pad($details['id_commande'], 6, '0', STR_PAD_LEFT) : '—' ?>
    </h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <?php if (!$details): ?>
        <div class="alert alert-danger">Commande introuvable.</div>
    <?php else: ?>

        <div class="d-flex align-items-center gap-3 mb-4">
            <?php
            $configStatuts = ['en_attente' => 'warning', 'approuvée' => 'success', 'envoyée' => 'info', 'annulée' => 'danger'];
            ?>
            <span class="badge bg-<?= $configStatuts[$details['statut_commande']] ?? 'secondary' ?> fs-6">
                <?= ucfirst($details['statut_commande']) ?>
            </span>
        </div>

        <div class="row g-4 mb-4">
            <!-- Informations client -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3">
                            <i class="bi bi-person me-2"></i>Client
                        </h6>
                        <p class="fw-bold mb-1"><?= $details['prenom_client'] ?> <?= $details['nom_client'] ?></p>
                        <p class="text-muted mb-1"><?= $details['email_client'] ?></p>
                        <hr class="my-2">
                        <p class="mb-1"><?= $details['numero'] ?> <?= $details['rue'] ?></p>
                        <p class="mb-0"><?= $details['code_postal'] ?> <?= $details['ville'] ?> — <?= $details['pays'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Informations commande -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3">
                            <i class="bi bi-bag me-2"></i>Commande
                        </h6>
                        <p class="mb-1"><span class="text-muted">Date :</span> <?= date('d/m/Y', strtotime($details['date_commande'])) ?></p>
                        <p class="mb-1">
                            <span class="text-muted">Paiement :</span>
                            <?php if ($details['statut_commande'] === 'annulée'): ?>
                                <span class="badge bg-danger">Annulé</span>
                            <?php elseif ($details['statut_paiement'] === 't' || $details['statut_paiement'] == 1): ?>
                                <span class="badge bg-success">Payé</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">En attente</span>
                            <?php endif; ?>
                        </p>
                        <p class="mb-0 fw-bold fs-5 mt-3">Total : <?= number_format((float)$details['total_commande'], 2, ',', ' ') ?> €</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lignes de commande -->
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Articles commandés
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Produit</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end px-4">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lignes)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Aucun article.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lignes as $ligne): ?>
                                <?php $lienProduit = 'index_.php?page=produit.php&detail=' . $ligne['id_produit']; ?>
                                <tr class="tr-clickable" data-href="<?= $lienProduit ?>"
                                    title="Voir le produit"
                                    tabindex="0" role="link">
                                    <td class="px-4 fw-medium">
                                        <?= htmlspecialchars($ligne['nom_produit']) ?>
                                        <i class="bi bi-box-arrow-up-right ms-2 text-muted small"></i>
                                    </td>
                                    <td class="text-center"><?= $ligne['quantite_commandee'] ?></td>
                                    <td class="text-end"><?= number_format((float)$ligne['prix_unitaire_achat'], 2, ',', ' ') ?> €</td>
                                    <td class="text-end px-4 fw-semibold">
                                        <?= number_format((float)$ligne['prix_unitaire_achat'] * (int)$ligne['quantite_commandee'], 2, ',', ' ') ?> €
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold px-4">Total</td>
                            <td class="text-end fw-bold px-4"><?= number_format((float)$details['total_commande'], 2, ',', ' ') ?> €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    <?php endif; ?>
    </div>
</div>
