<?php
$libellesStatuts = [
    'en_attente' => ['label' => 'En attente', 'class' => 'bg-warning text-dark'],
    'approuvée' => ['label' => 'Approuvée', 'class' => 'bg-info text-dark'],
    'envoyée' => ['label' => 'Expédiée', 'class' => 'bg-primary'],
    'annulée' => ['label' => 'Annulée', 'class' => 'bg-danger'],
];
$statut     = $commande['statut_commande'] ?? '';
$infoStatut = $libellesStatuts[$statut] ?? ['label' => $statut, 'class' => 'bg-secondary'];
?>

<div class="mb-3">
    <a href="index_.php?page=compte.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<!-- En-tête -->
<div class="theme-dashboard-bloc mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h5 class="fw-bold mb-1 theme-color-violet-bold">Commande #<?= $commande['id_commande'] ?></h5>
            <p class="text-muted small mb-0">
                Passée le <?= date('d/m/Y', strtotime($commande['date_commande'])) ?>
            </p>
        </div>
        <div class="text-end">
            <p class="fs-5 fw-bold mb-1 theme-color-rose">
                <?= number_format((float)$commande['total_commande'], 2, ',', ' ') ?> €
            </p>
            <span class="badge <?= $infoStatut['class'] ?>"><?= $infoStatut['label'] ?></span>
        </div>
    </div>

    <!-- Stepper : toujours visible, annulee = bloqué à l'étape 0 -->
    <?php
    $etapesDetail = [
        ['key' => 'en_attente', 'label' => 'Commande', 'icon' => 'bi-receipt'],
        ['key' => 'approuvée', 'label' => 'Approuvée', 'icon' => 'bi-box-seam'],
        ['key' => 'envoyée', 'label' => 'Expédiée', 'icon' => 'bi-truck'],
    ];
    $idxD = array_search($statut, array_column($etapesDetail, 'key'));
    $stepDetail = ($statut === 'annulée') ? -1
                : ($statut === 'envoyée'  ? count($etapesDetail) - 1
                : ($idxD !== false ? (int)$idxD : 0));
    ?>
    <div class="theme-stepper-icons mt-4">
        <?php foreach ($etapesDetail as $i => $etape): ?>
            <?php if ($i > 0): ?>
                <div class="theme-step-icon-line <?= ($statut !== 'annulee' && $i <= $stepDetail) ? 'done' : '' ?>"></div>
            <?php endif; ?>
            <div class="theme-step-icon <?= ($statut !== 'annulee' && $i < $stepDetail) ? 'done' : (($statut !== 'annulee' && $i === $stepDetail) ? 'active' : '') ?>">
                <div class="theme-step-icon-circle">
                    <i class="bi <?= $etape['icon'] ?>"></i>
                </div>
                <div class="theme-step-icon-label"><?= $etape['label'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4">

    <!-- Adresse -->
    <div class="col-md-4">
        <div class="theme-dashboard-bloc h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-1"></i>Livraison</h6>
            <p class="mb-1"><?= e($commande['prenom_client']) ?> <?= e($commande['nom_client']) ?></p>
            <p class="mb-0 text-muted small">
                <?= e($commande['numero']) ?> <?= e($commande['rue']) ?><br>
                <?= e($commande['code_postal']) ?> <?= e($commande['ville']) ?><br>
                <?= e($commande['pays']) ?>
            </p>
        </div>
    </div>

    <!-- Articles -->
    <div class="col-md-8">
        <div class="theme-dashboard-bloc">
            <h6 class="fw-bold mb-3"><i class="bi bi-bag me-1"></i>Articles</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lignes as $l): ?>
                            <tr class="tr-clickable"
                                data-href="index_.php?page=produit.php&id=<?= $l['id_produit'] ?>"
                                title="Voir le produit"
                                tabindex="0" role="link">
                                <td class="fw-medium">
                                    <?= e($l['nom_produit']) ?>
                                    <i class="bi bi-box-arrow-up-right ms-1 text-muted small"></i>
                                </td>
                                <td class="text-center"><?= $l['quantite_commandee'] ?></td>
                                <td class="text-end"><?= number_format((float)$l['prix_unitaire_achat'], 2, ',', ' ') ?> €</td>
                                <td class="text-end fw-semibold">
                                    <?= number_format((float)$l['prix_unitaire_achat'] * (int)$l['quantite_commandee'], 2, ',', ' ') ?> €
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold theme-color-rose">
                                <?= number_format((float)$commande['total_commande'], 2, ',', ' ') ?> €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
