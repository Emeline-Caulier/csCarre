<?php
// Fragment réutilisable : carte commande avec stepper
$statut = $cmd->statut_commande;
$sc = $statutConfig[$statut] ?? ['label' => $statut, 'badge' => 'bg-secondary', 'icon' => '', 'txt' => 'text-secondary'];
$idxStep = array_search($statut, array_column($etapesIcones, 'key'));
$step = ($idxStep !== false) ? (int)$idxStep : 0;
$annulee = ($statut === 'annulée');
// Pour envoyée : step = dernière étape (index 2), toutes les étapes sont "done"
if ($statut === 'envoyée') $step = count($etapesIcones) - 1;
?>
<div class="theme-order-card mb-4">

    <!-- En-tête -->
    <div class="theme-order-card-header">
        <div>
            <span class="theme-order-date">
                Commande du <?= date('d/m/Y', strtotime($cmd->date_commande)) ?>
            </span>
            <span class="theme-order-meta ms-3">
                <i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($cmd->date_commande)) ?>
            </span>
        </div>
        <span class="badge <?= $sc['badge'] ?>">
            <?php if ($sc['icon'] !== ''): ?><i class="bi <?= $sc['icon'] ?> me-1"></i><?php endif; ?>
            <?= $sc['label'] ?>
        </span>
    </div>

    <div class="theme-order-card-body">

        <!-- Stepper iconique : toujours visible dès le statut 0 -->
        <div class="theme-stepper-icons mb-4">
            <?php foreach ($etapesIcones as $i => $etape): ?>
                <?php if ($i > 0): ?>
                    <div class="theme-step-icon-line <?= (!$annulee && $i <= $step) ? 'done' : '' ?>"></div>
                <?php endif; ?>
                <div class="theme-step-icon <?= (!$annulee && $i < $step) ? 'done' : ((!$annulee && $i === $step) ? 'active' : '') ?>">
                    <div class="theme-step-icon-circle">
                        <i class="bi <?= $etape['icon'] ?>"></i>
                    </div>
                    <div class="theme-step-icon-label"><?= $etape['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- RÉSUMÉ + APERÇU ARTICLES -->
        <div class="row g-3">

            <!-- Carte statut -->
            <div class="col-md-5">
                <div class="theme-order-status-box">
                    <div class="theme-status-title">
                        <i class="bi <?= $sc['icon'] ?> <?= $sc['txt'] ?> fs-5"></i>
                        <?= $sc['label'] ?>
                    </div>
                    <div class="theme-status-row">
                        <span>Numéro de commande</span>
                        <span>#<?= $cmd->id_commande ?></span>
                    </div>
                    <div class="theme-status-row">
                        <span>Montant total</span>
                        <span><?= number_format((float)$cmd->total_commande, 2, ',', ' ') ?> €</span>
                    </div>
                    <a href="index_.php?page=compte.php&vue=commande&id=<?= $cmd->id_commande ?>"
                       class="btn btn-dark w-100 mt-2 fw-semibold">
                        <i class="bi bi-list-ul me-1"></i>Voir les détails
                    </a>
                </div>
            </div>

            <!-- Aperçu articles -->
            <div class="col-md-7">
                <div class="theme-order-preview-box">
                    <?php
                    $lignesPreview = $commandeDAO->getLignesCommande($cmd->id_commande);
                    $nbArticles = count($lignesPreview);
                    ?>
                    <p class="fw-semibold mb-2 text-muted small">
                        <i class="bi bi-bag me-1"></i><?= $nbArticles ?> article<?= $nbArticles > 1 ? 's' : '' ?>
                    </p>
                    <?php foreach (array_slice($lignesPreview, 0, 3) as $l): ?>
                        <div class="theme-order-preview-item tr-clickable"
                             data-href="index_.php?page=produit.php&id=<?= $l['id_produit'] ?>"
                             title="Voir le produit"
                             tabindex="0" role="link">
                            <i class="bi bi-gem text-muted theme-gem-icon"></i>
                            <div>
                                <div class="theme-item-name"><?= e($l['nom_produit']) ?></div>
                                <div class="theme-item-qty">Quantité : <?= (int)$l['quantite_commandee'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($nbArticles > 3): ?>
                        <p class="text-muted small mt-2 mb-0">
                            + <?= $nbArticles - 3 ?> autre<?= $nbArticles - 3 > 1 ? 's' : '' ?> article<?= $nbArticles - 3 > 1 ? 's' : '' ?>…
                        </p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
