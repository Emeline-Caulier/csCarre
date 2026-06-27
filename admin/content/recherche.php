<?php
require "src/php/utils/check_connexion.php";

$q = trim($_GET['q'] ?? '');
$qProtegee = htmlspecialchars($q);
$motifRecherche = '%' . $q . '%';
$estNumerique = ctype_digit($q);

$clients = [];
$produits = [];
$commandes = [];

if (strlen($q) >= 2) {

    // ── CLIENTS ───────────────────────────────────────────────────
    try {
        $stmt = $cnx->prepare(
            "SELECT id_client, nom_client, prenom_client, email_client
             FROM client
             WHERE unaccent(LOWER(nom_client)) LIKE unaccent(:q)
                OR unaccent(LOWER(prenom_client)) LIKE unaccent(:q)
                OR unaccent(LOWER(email_client)) LIKE unaccent(:q)
             ORDER BY nom_client
             LIMIT 30"
        );
        $stmt->bindValue(':q', $motifRecherche);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { error_log($e->getMessage()); }

    // ── PRODUITS ──────────────────────────────────────────────────
    try {
        $sql = "SELECT id_produit, nom_produit, prix::numeric::text AS prix, stock
                FROM produit
                WHERE unaccent(LOWER(nom_produit)) LIKE unaccent(:q)";
        $parametres = [':q' => $motifRecherche];

        if ($estNumerique) {
            $sql .= " OR id_produit = :id";
            $parametres[':id'] = (int)$q;
        }

        $sql .= " ORDER BY nom_produit LIMIT 30";
        $stmt = $cnx->prepare($sql);
        foreach ($parametres as $k => $v) {
            $stmt->bindValue($k, $v, $k === ':id' ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { error_log($e->getMessage()); }

    // ── COMMANDES ─────────────────────────────────────────────────
    try {
        // Cast total_commande money -> numeric::text (sinon préfixe '$' en locale en_US, qui rend (float) = 0)
        $sql = "SELECT cmd.id_commande, cmd.date_commande, cmd.total_commande::numeric::text AS total_commande,
                       cmd.statut_commande, cmd.statut_paiement,
                       cl.id_client, cl.nom_client, cl.prenom_client
                FROM commande cmd
                JOIN client cl ON cmd.id_client = cl.id_client
                WHERE unaccent(LOWER(cl.nom_client))    LIKE unaccent(:q)
                   OR unaccent(LOWER(cl.prenom_client)) LIKE unaccent(:q)";
        $parametres = [':q' => $motifRecherche];

        if ($estNumerique) {
            $sql .= " OR cmd.id_commande = :id";
            $parametres[':id'] = (int)$q;
        }

        $sql .= " ORDER BY cmd.date_commande DESC LIMIT 30";
        $stmt = $cnx->prepare($sql);
        foreach ($parametres as $k => $v) {
            $stmt->bindValue($k, $v, $k === ':id' ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { error_log($e->getMessage()); }
}

$totalResultats = count($clients) + count($produits) + count($commandes);

$libellesStatuts = [
    'en_attente' => ['label' => 'En attente', 'class' => 'bg-warning text-dark'],
    'approuvée' => ['label' => 'Approuvée', 'class' => 'bg-success'],
    'envoyée' => ['label' => 'Envoyée', 'class' => 'bg-info text-dark'],
    'annulée' => ['label' => 'Annulée', 'class' => 'bg-danger'],
];
?>

<div class="theme-section">
    <h2 class="theme-section-title">Résultats de recherche</h2>

    <div class="p-4">

        <!-- En-tête résultats -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <?php if ($q !== ''): ?>
                <span class="fw-semibold theme-color-violet-bold">
                    <?= $totalResultats ?> résultat<?= $totalResultats > 1 ? 's' : '' ?>
                    pour « <?= $qProtegee ?> »
                </span>
                <a href="index_.php?page=accueil.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Effacer
                </a>
            <?php else: ?>
                <span class="text-muted">Saisissez un nom, prénom ou numéro de commande.</span>
            <?php endif; ?>
        </div>

        <?php if (strlen($q) >= 2 && $totalResultats === 0): ?>
            <div class="theme-dashboard-bloc text-center py-5">
                <i class="bi bi-search fs-1 d-block mb-3 theme-empty-icon-lg"></i>
                <p class="fw-semibold theme-color-violet-bold">Aucun résultat pour « <?= $qProtegee ?> »</p>
                <p class="text-muted small">Vérifiez l'orthographe ou essayez un autre terme.</p>
            </div>
        <?php endif; ?>

        <!-- ── CLIENTS ──────────────────────────────────────────── -->
        <?php if (!empty($clients)): ?>
        <div class="theme-dashboard-bloc mb-4">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-people me-2"></i>Clients
                <span class="badge ms-2 theme-badge-violet"><?= count($clients) ?></span>
            </h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>E-mail</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($clients as $c): ?>
                        <tr>
                            <td class="text-muted small">#<?= $c['id_client'] ?></td>
                            <td><?= htmlspecialchars($c['prenom_client']) ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($c['nom_client']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($c['email_client']) ?></td>
                            <td class="text-end">
                                <a href="index_.php?page=client.php&detail=<?= $c['id_client'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── PRODUITS ─────────────────────────────────────────── -->
        <?php if (!empty($produits)): ?>
        <div class="theme-dashboard-bloc mb-4">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-gem me-2"></i>Produits
                <span class="badge ms-2 theme-badge-teal"><?= count($produits) ?></span>
            </h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nom du produit</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td class="text-muted small">#<?= $p['id_produit'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($p['nom_produit']) ?></td>
                            <td><?= number_format((float)$p['prix'], 2, ',', ' ') ?> €</td>
                            <td>
                                <?php if ((int)$p['stock'] === 0): ?>
                                    <span class="badge bg-danger">Rupture</span>
                                <?php elseif ((int)$p['stock'] <= 5): ?>
                                    <span class="badge bg-warning text-dark"><?= $p['stock'] ?> restants</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $p['stock'] ?> en stock</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="index_.php?page=produit.php&detail=<?= $p['id_produit'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── COMMANDES ────────────────────────────────────────── -->
        <?php if (!empty($commandes)): ?>
        <div class="theme-dashboard-bloc">
            <h6 class="dash-section-title text-uppercase fw-bold mb-3">
                <i class="bi bi-bag me-2"></i>Commandes
                <span class="badge ms-2 theme-badge-rose"><?= count($commandes) ?></span>
            </h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($commandes as $cmd): ?>
                        <?php $infoStatut = $libellesStatuts[$cmd['statut_commande']] ?? ['label' => $cmd['statut_commande'], 'class' => 'bg-secondary']; ?>
                        <tr>
                            <td class="text-muted small">#<?= $cmd['id_commande'] ?></td>
                            <td>
                                <a href="index_.php?page=vues/client/detail.php&id=<?= $cmd['id_client'] ?>"
                                   class="fw-semibold text-decoration-none theme-color-violet-bold">
                                    <?= htmlspecialchars($cmd['prenom_client'] . ' ' . $cmd['nom_client']) ?>
                                </a>
                            </td>
                            <td class="text-muted small">
                                <?= date('d/m/Y', strtotime($cmd['date_commande'])) ?>
                            </td>
                            <td><?= number_format((float)$cmd['total_commande'], 2, ',', ' ') ?> €</td>
                            <td><span class="badge <?= $infoStatut['class'] ?>"><?= $infoStatut['label'] ?></span></td>
                            <td class="text-end">
                                <a href="index_.php?page=commande.php&detail=<?= $cmd['id_commande'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
