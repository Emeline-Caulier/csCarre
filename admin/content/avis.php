<?php
require "src/php/utils/check_connexion.php";

$avisDAO = new AvisDAO($cnx);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_avis'])) {
    $id = (int)$_POST['id_avis'];
    match ($_POST['action']) {
        'approuver' => $avisDAO->updateStatus($id, 'approuvé'),
        'refuser' => $avisDAO->updateStatus($id, 'refusé'),
        default => null,
    };
    header("Location: index_.php?page=avis.php");
    exit;
}

$listeAvis = $avisDAO->getAllAvis();
$enAttenteCount = count(array_filter($listeAvis, fn($a) => $a->modere === 'en_attente'));

$filtresDisponibles = [
    '' => 'Tous les avis',
    'approuvé' => 'Approuvés',
    'refusé' => 'Refusés',
    'en_attente' => 'En attente',
];
$filtre = isset($_GET['filtre']) && array_key_exists($_GET['filtre'], $filtresDisponibles) ? $_GET['filtre'] : '';
if ($filtre !== '') {
    $listeAvis = array_values(array_filter($listeAvis, fn($a) => $a->modere === $filtre));
}
?>

<div class="theme-section">
    <h2 class="theme-section-title">Avis</h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="badge rounded-pill bg-light text-dark border"><?= count($listeAvis) ?> au total</span>
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
                           href="index_.php?page=avis.php<?= $valeur !== '' ? '&filtre=' . $valeur : '' ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                <tr>
                    <th class="th-photo-avis">Photo</th>
                    <th>Client / Produit</th>
                    <th>Note</th>
                    <th class="th-commentaire-avis">Commentaire</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($listeAvis)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-chat-square-dots fs-1 d-block mb-2"></i>
                            Aucun avis à afficher pour le moment.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listeAvis as $a): ?>
                        <tr class="<?= $a->modere === 'en_attente' ? 'fw-bold' : '' ?>">
                            <td>
                                <?php if (!empty($a->photo)): ?>
                                    <img src="<?= $a->photo ?>"
                                         class="rounded border avis-photo-ouvrir">
                                <?php else: ?>
                                    <div class="bg-light rounded text-center border d-flex align-items-center justify-content-center avis-photo-placeholder">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($a->prenom_client . ' ' . $a->nom_client) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($a->nom_produit) ?></small><br>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($a->date_avis)) ?></small>
                            </td>

                            <td>
                                <span class="text-warning"><?= str_repeat('★', $a->note_etoiles) ?></span><span class="text-muted"><?= str_repeat('★', 5 - $a->note_etoiles) ?></span>
                                <div class="small text-muted"><?= $a->note_etoiles ?>/5</div>
                            </td>

                            <td>
                                <small class="text-muted" title="<?= htmlspecialchars($a->commentaire) ?>">
                                    "<?= htmlspecialchars($a->commentaire) ?>"
                                </small>
                            </td>

                            <td>
                                <?php if ($a->modere === 'en_attente'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>En attente</span>
                                <?php elseif ($a->modere === 'approuvé'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approuvé</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Refusé</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">

                                    <?php if ($a->modere === 'en_attente'): ?>
                                        <form method="post" action="index_.php?page=avis.php">
                                            <input type="hidden" name="action" value="approuver">
                                            <input type="hidden" name="id_avis" value="<?= $a->id_avis ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approuver">
                                                <i class="bi bi-check-lg me-1"></i>Approuver
                                            </button>
                                        </form>
                                        <form method="post" action="index_.php?page=avis.php">
                                            <input type="hidden" name="action" value="refuser">
                                            <input type="hidden" name="id_avis" value="<?= $a->id_avis ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Refuser">
                                                <i class="bi bi-x-circle me-1"></i>Refuser
                                            </button>
                                        </form>

                                    <?php elseif ($a->modere === 'approuvé'): ?>
                                        <form method="post" action="index_.php?page=avis.php">
                                            <input type="hidden" name="action" value="refuser">
                                            <input type="hidden" name="id_avis" value="<?= $a->id_avis ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Refuser">
                                                <i class="bi bi-x-circle me-1"></i>Refuser
                                            </button>
                                        </form>

                                    <?php elseif ($a->modere === 'refusé'): ?>
                                        <form method="post" action="index_.php?page=avis.php">
                                            <input type="hidden" name="action" value="approuver">
                                            <input type="hidden" name="id_avis" value="<?= $a->id_avis ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approuver">
                                                <i class="bi bi-check-lg me-1"></i>Approuver
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
