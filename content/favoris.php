<?php
$listeEnvieDAO = new ListeEnvieDAO($cnx);

$id_session = session_id();
$id_client = isset($_SESSION['client_id']) ? (int)$_SESSION['client_id'] : null;

$favoris = $listeEnvieDAO->getFavorisAvecProduits($id_session, $id_client);
?>

<div class="theme-section">
    <h2 class="theme-section-title">Ma liste d'envie</h2>
    <div class="p-4">

        <?php if (empty($favoris)): ?>
            <div class="text-center py-5 theme-dashboard-bloc">
                <i class="bi bi-heart-slash fs-1 d-block mb-3 text-muted"></i>
                <p class="fw-semibold text-muted">Votre liste d'envie est vide.</p>
                <a href="index_.php?page=catalogue.php" class="btn btn-primary mt-3">
                    Découvrir nos bijoux
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4" id="liste-envie-grille">
                <?php foreach ($favoris as $f):
                    $prixBrut = (float)str_replace(['€', ' ', ','], ['', '', '.'], (string)$f['prix']);
                    $prixFinal = $f['prix_reduit'] !== null ? (float)$f['prix_reduit'] : $prixBrut;
                    $enStock = (int)$f['stock'] > 0;
                ?>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="carte-produit-maquette produit-card w-100">
                            <a href="index_.php?page=produit.php&id=<?= $f['id_produit'] ?>" class="text-decoration-none">
                                <div class="img-container">
                                    <?php if ($f['image_url']): ?>
                                        <img src="admin/<?= htmlspecialchars($f['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                                             alt="<?= htmlspecialchars($f['nom_produit'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?php else: ?>
                                        <div class="catalogue-img-vide"><i class="bi bi-image fs-1"></i></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="carte-produit-corps">
                                <div class="carte-produit-entete">
                                    <a href="index_.php?page=produit.php&id=<?= $f['id_produit'] ?>" class="text-decoration-none">
                                        <h5 class="produit-titre"><?= htmlspecialchars($f['nom_produit'], ENT_QUOTES, 'UTF-8') ?></h5>
                                    </a>
                                    <button class="btn-wishlist-icon active" data-id="<?= $f['id_produit'] ?>" aria-label="Retirer des favoris">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </div>
                                <div class="carte-produit-pied">
                                    <div>
                                        <?php if ($f['prix_reduit'] !== null): ?>
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <span class="text-muted text-decoration-line-through small">
                                                    <?= number_format($prixBrut, 2, ',', ' ') ?> €
                                                </span>
                                                <span class="badge theme-badge-teal theme-badge-sm">
                                                    -<?= round((float)$f['reduction_pourcent']) ?>%
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <span class="prix-maquette"><?= number_format($prixFinal, 2, ',', ' ') ?> €</span>
                                    </div>
                                    <?php if ($enStock): ?>
                                        <button class="btn-panier-pink-maquette" data-id="<?= $f['id_produit'] ?>" aria-label="Ajouter au panier">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Indisponible</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
