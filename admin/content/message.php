<?php
require "src/php/utils/check_connexion.php";

$messageDAO = new MessageDAO($cnx);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_message'])) {
    $id = (int)$_POST['id_message'];
    $statutsValides = ['non_lu', 'lu', 'repondu'];
    if ($_POST['action'] === 'statut' && in_array($_POST['statut'] ?? '', $statutsValides, true)) {
        $messageDAO->changerStatut($id, $_POST['statut']);
    }
    header("Location: index_.php?page=message.php");
    exit;
}

$messages = $messageDAO->getAllMessages();
$nombreNonLus = count(array_filter($messages, fn(Message $m) => $m->statut === 'non_lu'));

$configStatuts = [
    'non_lu' => ['label' => 'Non lu', 'class' => 'bg-warning text-dark'],
    'lu' => ['label' => 'Lu', 'class' => 'bg-secondary'],
    'repondu' => ['label' => 'Répondu', 'class' => 'bg-success'],
];

$filtresDisponibles = [
    '' => 'Tous les messages',
    'non_lu' => 'Non lus',
    'lu' => 'Lus',
    'repondu' => 'Répondus',
];
$filtre = isset($_GET['filtre']) && array_key_exists($_GET['filtre'], $filtresDisponibles) ? $_GET['filtre'] : '';
if ($filtre !== '') {
    $messages = array_values(array_filter($messages, fn(Message $m) => $m->statut === $filtre));
}
?>

<div class="theme-section">
    <h2 class="theme-section-title">Messagerie</h2>
    <div class="p-4 theme-dashboard-bloc">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="badge rounded-pill bg-light text-dark border"><?= count($messages) ?> au total</span>
            <?php if ($nombreNonLus > 0): ?>
                <span class="badge rounded-pill bg-danger">
                    <?= $nombreNonLus ?> non lu<?= $nombreNonLus > 1 ? 's' : '' ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-funnel me-1"></i><?= $filtresDisponibles[$filtre] ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php foreach ($filtresDisponibles as $valeur => $libelle): ?>
                    <li>
                        <a class="dropdown-item <?= $filtre === $valeur ? 'active' : '' ?>"
                           href="index_.php?page=message.php<?= $valeur !== '' ? '&filtre=' . $valeur : '' ?>">
                            <?= $libelle ?>
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
                        <th>#</th>
                        <th>Date</th>
                        <th>Contact</th>
                        <th>Client lié</th>
                        <th>N° commande</th>
                        <th>Sujet</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr class="<?= $msg->statut === 'non_lu' ? 'fw-bold' : '' ?>">
                        <td class="px-4 text-muted small">#<?= $msg->id_message ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($msg->date_message)) ?></td>
                        <td>
                            <?= $msg->nom_contact ?><br>
                            <small class="text-muted"><?= $msg->email_contact ?></small>
                        </td>
                        <td>
                            <?php if ($msg->nom_client): ?>
                                <a href="index_.php?page=commande.php&id_client=<?= $msg->id_client ?>"
                                   class="text-decoration-none">
                                    <?= $msg->prenom_client . ' ' . $msg->nom_client ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($msg->id_commande): ?>
                                <span>#<?= $msg->id_commande ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $msg->sujet ?></td>
                        <td>
                            <?php $infoStatut = $configStatuts[$msg->statut] ?? ['label' => $msg->statut, 'class' => 'bg-secondary']; ?>
                            <span class="badge <?= $infoStatut['class'] ?>"><?= $infoStatut['label'] ?></span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-voir-message"
                                        data-id="<?= $msg->id_message ?>"
                                        data-nom="<?= $msg->nom_contact ?>"
                                        data-email="<?= $msg->email_contact ?>"
                                        data-sujet="<?= $msg->sujet ?>"
                                        data-contenu="<?= $msg->contenu ?>"
                                        data-date="<?= date('d/m/Y H:i', strtotime($msg->date_message)) ?>"
                                        data-photo="<?= $msg->photo ?? '' ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalMessage">
                                    <i class="bi bi-envelope-open me-1"></i>Voir
                                </button>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-tag me-1"></i>Statut
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php foreach ($configStatuts as $valeur => $info): ?>
                                            <?php if ($valeur !== $msg->statut): ?>
                                                <li>
                                                    <form method="post" action="index_.php?page=message.php">
                                                        <input type="hidden" name="action" value="statut">
                                                        <input type="hidden" name="id_message" value="<?= $msg->id_message ?>">
                                                        <input type="hidden" name="statut" value="<?= $valeur ?>">
                                                        <button type="submit" class="dropdown-item">
                                                            Marquer comme <?= strtolower($info['label']) ?>
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($messages)): ?>
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-envelope fs-1 d-block mb-2"></i>
                Aucun message reçu.
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<!-- Modal lecture seule : affiche le détail d'un message client (expéditeur, date,
     sujet, contenu et photo jointe éventuelle). Rempli en JS au clic sur une ligne du tableau. -->
<div class="modal fade" id="modalMessage" tabindex="-1" aria-labelledby="modalMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMessageLabel">Détail du message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-3">De</dt>
                    <dd class="col-sm-9" id="modal-nom-email"></dd>
                    <dt class="col-sm-3">Date</dt>
                    <dd class="col-sm-9" id="modal-date"></dd>
                    <dt class="col-sm-3">Sujet</dt>
                    <dd class="col-sm-9" id="modal-sujet"></dd>
                </dl>
                <hr>
                <p id="modal-contenu" class="mb-3 modal-contenu-message"></p>
                <div id="modal-photo-wrapper" class="d-none">
                    <img id="modal-photo" src="" alt="Pièce jointe" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</div>
