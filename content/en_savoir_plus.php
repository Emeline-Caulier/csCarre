<?php
$messageDAO = new MessageDAO($cnx);
$commandeDAO = new CommandeDAO($cnx);

$idClient = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;
$estConnecte = $idClient !== null;

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $numCommande = isset($_POST['num_commande']) && $_POST['num_commande'] !== '' ? (int)$_POST['num_commande'] : null;

    if ($estConnecte) {
        $nom = $_SESSION['client_prenom'] . ' ' . $_SESSION['client_nom'];
        $email = $_SESSION['client_email'];
    }

    if ($nom === '' || $email === '' || $sujet === '' || $contenu === '') {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'L\'adresse e-mail saisie n\'est pas valide.';
    } else {
        $ok = $messageDAO->sendMessage($idClient, $nom, $email, $numCommande, $sujet, $contenu);
        if ($ok) {
            header('Location: index_.php?page=en_savoir_plus.php&success=contact');
            exit;
        }
        $erreur = 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer.';
    }
}

$commandes = $estConnecte ? $commandeDAO->getCommandesByClient($idClient) : [];
?>

<div class="theme-section">

    <?php if (isset($_GET['success']) && $_GET['success'] === 'contact'): ?>
        <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Notre histoire -->
    <div class="section-bloc">
        <h2 class="theme-section-title">Notre histoire</h2>
        <div class="theme-dashboard-bloc d-flex align-items-center justify-content-center gap-5 mb-4 py-4 bg-white">
            <img src="admin/assets/images/logo_acrylia.jpg" alt="Logo Acrylia" class="theme-histoire-logo">
            <div>
                <p class="text-muted mb-3">
                    Acrylia est née en 2019 d'une passion partagée pour les formes géométriques et les couleurs qui claquent.
                    Petite marque artisanale, nous dessinons et fabriquons chaque pièce à la main dans notre atelier,
                    en privilégiant le plexiglas recyclé et les circuits courts.
                </p>
                <p class="text-muted mb-3">
                    Inspirée du pop art et de l'esthétique des années 90, notre collection mêle hexagones, triangles et étoiles
                    dans des teintes électriques. Chaque bijou est unique : aucune découpe n'est identique, et nous assemblons
                    chaque pièce avec soin pour vous offrir un bijou qui ne ressemble qu'à vous.
                </p>
                <p class="text-muted mb-0">
                    Plus qu'une boutique, Acrylia est un petit atelier où l'on prend le temps de bien faire.
                    Si vous craquez pour une pièce, sachez qu'elle a été pensée, dessinée et assemblée avec amour.
                </p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="theme-dashboard-bloc text-center p-4 h-100 bg-white">
                    <i class="bi bi-gem fs-1 theme-color-rose d-block mb-2"></i>
                    <p class="fw-semibold mb-1">Fait main</p>
                    <p class="text-muted small mb-0">Chaque bijou est façonné à la main dans notre atelier</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="theme-dashboard-bloc text-center p-4 h-100 bg-white">
                    <i class="bi bi-lightning fs-1 theme-color-violet-bold d-block mb-2"></i>
                    <p class="fw-semibold mb-1">Plexi power</p>
                    <p class="text-muted small mb-0">Géométrie funky — des formes bien calculées</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="theme-dashboard-bloc text-center p-4 h-100 bg-white">
                    <i class="bi bi-palette fs-1 d-block mb-2 text-theme-teal"></i>
                    <p class="fw-semibold mb-1">Couleurs vives</p>
                    <p class="text-muted small mb-0">Des couleurs qui font sourire, à chaque pièce</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="theme-dashboard-bloc text-center p-4 h-100 bg-white">
                    <i class="bi bi-shop fs-1 theme-color-rose d-block mb-2"></i>
                    <p class="fw-semibold mb-1">Artisanat belge</p>
                    <p class="text-muted small mb-0">Boutique d'art et d'artisanat, recommandée à 100 %</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coordonnées -->
    <div class="section-bloc">
        <div class="theme-dashboard-bloc mb-0 bg-white">
            <p class="fw-semibold mb-3">Retrouvez-nous</p>
            <ul class="list-unstyled text-muted mb-0">
                <li class="mb-2">
                    <i class="bi bi-geo-alt me-2 theme-color-rose"></i>
                    12 rue des Artisans, 1000 Bruxelles, Belgique
                </li>
                <li class="mb-2">
                    <i class="bi bi-telephone me-2 theme-color-rose"></i>
                    <a href="tel:+3225551234" class="text-muted text-decoration-none">+32 2 555 12 34</a>
                </li>
                <li class="mb-2">
                    <i class="bi bi-envelope me-2 theme-color-rose"></i>
                    <a href="mailto:bonjour@acrylia.shop" class="text-muted text-decoration-none">bonjour@acrylia.shop</a>
                </li>
                <li>
                    <i class="bi bi-clock me-2 theme-color-rose"></i>
                    Atelier ouvert du mardi au samedi, 10h - 18h
                </li>
            </ul>
        </div>
    </div>

    <!-- Formulaire de contact -->
    <div class="section-bloc">
        <h2 class="theme-section-title mb-4">Nous contacter</h2>

        <?php if ($erreur !== ''): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form method="post" action="index_.php?page=en_savoir_plus.php">
                    <input type="hidden" name="action" value="contact">

                    <div class="row g-3">

                        <?php if (!$estConnecte): ?>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" required
                                       value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                       placeholder="Votre nom et prénom">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                       placeholder="votre@email.fr"
                                       pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                                       title="Veuillez saisir une adresse email valide (ex : nom@exemple.fr).">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                                <input type="text" name="sujet" class="form-control" required
                                       value="<?= htmlspecialchars($_POST['sujet'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                       placeholder="Ex : question sur ma commande, retour produit…">
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3 rounded theme-bg-light">
                                    <i class="bi bi-person-circle fs-4 theme-color-violet-bold"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold">
                                            <?= htmlspecialchars($_SESSION['client_prenom'] . ' ' . $_SESSION['client_nom'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                        <p class="mb-0 text-muted small"><?= htmlspecialchars($_SESSION['client_email'], ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($commandes)): ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Commande concernée
                                        <span class="text-muted fw-normal small">(facultatif)</span>
                                    </label>
                                    <select name="num_commande" class="form-select">
                                        <option value="">— Aucune commande spécifique —</option>
                                        <?php foreach ($commandes as $cmd): ?>
                                            <option value="<?= $cmd->id_commande ?>">
                                                #<?= $cmd->id_commande ?> — <?= date('d/m/Y', strtotime($cmd->date_commande)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                                    <input type="text" name="sujet" class="form-control" required
                                           value="<?= htmlspecialchars($_POST['sujet'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Ex : question sur ma commande, retour produit…">
                                </div>
                            <?php else: ?>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                                    <input type="text" name="sujet" class="form-control" required
                                           value="<?= htmlspecialchars($_POST['sujet'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                           placeholder="Ex : question sur ma commande, retour produit…">
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea name="contenu" class="form-control" rows="6" required
                                      placeholder="Décrivez votre demande…"><?= htmlspecialchars($_POST['contenu'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i>Envoyer le message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
