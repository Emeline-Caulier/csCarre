<div class="theme-section">
    <h2 class="theme-section-title">
        <?= $idClient ? 'Commandes de ' . htmlspecialchars($clientNom) : 'Commandes' ?>
    </h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <?php if ($idClient): ?>
                <a href="index_.php?page=client.php" class="btn btn-outline-secondary btn-sm">← Clients</a>
            <?php endif; ?>
            <span class="badge rounded-pill bg-light text-dark border"><?= count($commandes) ?> au total</span>
            <?php if ($enAttenteCount > 0): ?>
                <span class="badge rounded-pill bg-danger"><?= $enAttenteCount ?> en attente</span>
            <?php endif; ?>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-funnel me-1"></i><?= $filtresDisponibles[$filtre] ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php foreach ($filtresDisponibles as $valeur => $label): ?>
                    <li>
                        <a class="dropdown-item <?= $filtre === $valeur ? 'active' : '' ?>"
                           href="<?= $lienBase ?><?= $valeur !== '' ? '&filtre=' . $valeur : '' ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th class="px-4 text-start th-id-commande">#</th>
                        <th class="text-start">Date</th>
                        <?php if (!$idClient): ?>
                            <th class="text-start">Client</th>
                        <?php endif; ?>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($commandes as $cmd): ?>
                    <tr>
                        <td class="px-4 text-muted small">#<?= $cmd->id_commande ?></td>
                        <td><?= date('d/m/Y', strtotime($cmd->date_commande)) ?></td>
                        <?php if (!$idClient): ?>
                            <td>
                                <a href="index_.php?page=commande.php&id_client=<?= $cmd->id_client ?>"
                                   class="text-decoration-none fw-medium">
                                    <?= $cmd->prenom_client ?> <?= $cmd->nom_client ?>
                                </a>
                            </td>
                        <?php endif; ?>
                        <td class="text-center fw-bold"><?= number_format((float)$cmd->total_commande, 2, ',', ' ') ?> €</td>
                        <td class="text-center">
                            <?php if ($cmd->statut_commande === 'annulée'): ?>
                                <span class="badge bg-danger">Annulé</span>
                            <?php else: ?>
                                <form method="post" action="index_.php?page=commande.php" class="d-inline">
                                    <input type="hidden" name="action" value="toggle_paiement">
                                    <input type="hidden" name="id_commande" value="<?= $cmd->id_commande ?>">
                                    <input type="hidden" name="statut_paiement" value="<?= $cmd->statut_paiement ? 0 : 1 ?>">
                                    <?php if ($idClient): ?><input type="hidden" name="id_client" value="<?= $idClient ?>"><?php endif; ?>
                                    <?php if ($filtre !== ''): ?><input type="hidden" name="filtre" value="<?= $filtre ?>"><?php endif; ?>
                                    <button type="submit" class="btn btn-sm <?= $cmd->statut_paiement ? 'btn-success' : 'btn-outline-secondary' ?>"
                                            title="<?= $cmd->statut_paiement ? 'Marquer non payé' : 'Marquer comme payé' ?>">
                                        <?php if ($cmd->statut_paiement): ?>
                                            <i class="bi bi-check-lg me-1"></i>Payé
                                        <?php else: ?>
                                            En attente
                                        <?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?= $configStatuts[$cmd->statut_commande] ?? 'secondary' ?>">
                                <?= ucfirst($cmd->statut_commande) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <form method="post" action="index_.php?page=commande.php" class="d-flex gap-1 align-items-center">
                                    <input type="hidden" name="action" value="update_statut">
                                    <input type="hidden" name="id_commande" value="<?= $cmd->id_commande ?>">
                                    <?php if ($idClient): ?>
                                        <input type="hidden" name="id_client" value="<?= $idClient ?>">
                                    <?php endif; ?>
                                    <?php if ($filtre !== ''): ?>
                                        <input type="hidden" name="filtre" value="<?= $filtre ?>">
                                    <?php endif; ?>
                                    <select name="statut_commande" class="form-select form-select-sm select-statut-commande">
                                        <?php foreach ($statutsDisponibles as $statutValeur): ?>
                                            <option value="<?= $statutValeur ?>" <?= $cmd->statut_commande === $statutValeur ? 'selected' : '' ?>>
                                                <?= ucfirst($statutValeur) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Enregistrer">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <a href="index_.php?page=commande.php&detail=<?= $cmd->id_commande ?>"
                                   class="btn btn-sm btn-outline-secondary"
                                   title="Voir les détails">
                                    <i class="bi bi-eye me-1"></i>Détails
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($commandes)): ?>
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                Aucune commande trouvée.
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>
