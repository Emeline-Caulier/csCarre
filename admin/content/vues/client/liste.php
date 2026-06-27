<div class="theme-section">
    <h2 class="theme-section-title">Clients</h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-end align-items-center mb-4">
        <span class="badge rounded-pill bg-light text-dark border"><?= count($clients) ?> au total</span>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Ville</th>
                        <th>Pays</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td class="px-4 text-muted small">#<?= $c->id_client ?></td>
                        <td class="fw-medium"><?= $c->nom_client ?></td>
                        <td><?= $c->prenom_client ?></td>
                        <td class="text-muted"><?= $c->email_client ?></td>
                        <td><?= $c->ville ?></td>
                        <td><?= $c->pays ?></td>
                        <td class="text-center px-4">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index_.php?page=client.php&detail=<?= $c->id_client ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-person me-1"></i>Détail
                                </a>
                                <a href="index_.php?page=commande.php&id_client=<?= $c->id_client ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-bag me-1"></i>Commandes
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($clients)): ?>
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                Aucun client enregistré.
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>
