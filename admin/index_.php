<?php
// Point d'entrée de l'administration
ob_start(); // Buffer la sortie pour que header() fonctionne depuis les pages incluses
session_start();
require "src/php/utils/all_includes.php";

// Préfixe pour les chemins d'assets dans les fichiers partagés (header, footer)
// Vide ici car on est déjà dans le dossier admin/
$urlBase = '';

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['page'] = $_SESSION['page'] ?? 'accueil.php';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <title>Acrylia — Administration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js" defer></script>
    <script src="assets/js/utils.js" defer></script>
    <script src="assets/js/ui.js" defer></script>
    <script src="assets/js/admin-produits.js" defer></script>
    <script src="assets/js/admin-promotions.js" defer></script>
    <script src="assets/js/admin-accueil-config.js" defer></script>
    <script src="assets/js/produit.js" defer></script>
    <script src="assets/js/search.js" defer></script>
    <script src="assets/js/message.js" defer></script>
    <script src="assets/js/avis.js" defer></script>
</head>
<body class="admin">
<div id="wrapper">

    <header id="header">
        <?php include 'src/php/utils/header.php'; ?>
        <?php include 'src/php/utils/admin_menu.php'; ?>
    </header>

    <main id="main_admin">
        <section id="contenu">
            <?php
            if (!isset($_SESSION['admin_id'])) {
                $path = "content/login.php";
            } else {
                $_SESSION['page'] = $_GET['page'] ?? "accueil.php";
                $path = "content/" . $_SESSION['page'];
            }

            if (isset($path) && file_exists($path)) {
                include $path;
            } else {
                include "content/page404.php";
            }
            ?>
        </section>
    </main>

    <!-- Modal générique de confirmation (Oui / Annuler), déclenchée par confirmerAction() en JS.
         Remplace les confirm() natifs pour toutes les actions destructives côté admin. -->
    <div class="modal fade" id="modalConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirmation</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-confirm-message"></div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger btn-sm" id="btn-modal-confirm">Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'src/php/utils/footer.php'; ?>

</div>
</body>
</html>
