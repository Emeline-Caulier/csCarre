<!-- Vue : toutes les commandes -->
<?php
$statutConfig2 = [
    'en_attente' => ['label' => 'En attente', 'badge' => 'bg-warning text-dark'],
    'approuvée' => ['label' => 'Approuvée',  'badge' => 'bg-info text-dark'],
    'envoyée' => ['label' => 'Expédiée',   'badge' => 'bg-primary'],
    'annulée' => ['label' => 'Annulée',    'badge' => 'bg-danger'],
];
?>
<div class="mb-3">
    <a href="index_.php?page=compte.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="theme-dashboard-bloc">
    <h5 class="fw-bold mb-4 theme-color-violet">
        <i class="bi bi-list-ul me-2"></i>Toutes mes commandes
    </h5>

    <?php if (empty($commandes)): ?>
        <p class="text-muted text-center py-4">Aucune commande trouvée.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
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
                <?php foreach ($commandes as $cmd):
                    $sc2 = $statutConfig2[$cmd->statut_commande] ?? ['label' => $cmd->statut_commande, 'badge' => 'bg-secondary'];
                ?>
                <tr>
                    <td class="fw-semibold">#<?= $cmd->id_commande ?></td>
                    <td><?= date('d/m/Y', strtotime($cmd->date_commande)) ?></td>
                    <td class="fw-bold theme-color-rose">
                        <?= number_format((float)$cmd->total_commande, 2, ',', ' ') ?> €
                    </td>
                    <td><span class="badge <?= $sc2['badge'] ?>"><?= $sc2['label'] ?></span></td>
                    <td>
                        <a href="index_.php?page=compte.php&vue=commande&id=<?= $cmd->id_commande ?>"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i>Voir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
