<?php
$produitDAO = new ProduitDAO($cnx);
$avisDAO = new AvisDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produit = $id > 0 ? $produitDAO->getProduitById($id) : null;
$images = $produit ? $produitDAO->getImagesByProduit($id) : [];
$promoData = $promotionDAO->getPromoActive($id);
$prixPromo = $promoData ? $promoData->prix_reduit : null;
$pourcent = $promoData ? $promoData->reduction_pourcent : null;
$moyenne = $produit ? $avisDAO->getMoyenneByProduit($id) : 0.0;
$avis = $produit ? $avisDAO->getAvisByProduit($id) : [];
$nbAvis = count($avis);
// Recherche de produits similaires : d'abord par mots-clés du titre, puis fallback catégorie
$similaires = [];
if ($produit) {
    $motsVides = ['avec', 'sans', 'pour', 'dans', 'sur', 'sous', 'par', 'les', 'des',
                  'une', 'the', 'and', 'von', 'aux', 'bague', 'collier', 'bracelet',
                  'boucle', 'boucles'];
    $motsCles = array_values(array_filter(
        explode(' ', mb_strtolower(preg_replace('/[^a-zA-ZÀ-ÿ\s]/', '', $produit->nom_produit))),
        fn($m) => mb_strlen($m) > 3 && !in_array($m, $motsVides, true)
    ));
    if (!empty($motsCles)) {
        $similaires = $produitDAO->getSimilairesByMotsCles($id, $motsCles);
    }
    if (empty($similaires)) {
        $similairesRaw = $produitDAO->getProduitsByCategorie($produit->id_categorie);
        $similaires = array_slice(
            array_values(array_filter($similairesRaw, fn($p) => $p->id_produit !== $produit->id_produit)),
            0, 4
        );
    }
}
$promotionsSimilaires = !empty($similaires) ? $promotionDAO->getAllIndexedByProduit() : [];
?>

<div class="theme-section">

    <?php if (!$produit): ?>
        <h2 class="theme-section-title">Produit introuvable</h2>
        <div class="p-4 text-center py-5">
            <i class="bi bi-gem fs-1 d-block mb-3 theme-empty-icon-lg"></i>
            <p class="fw-semibold theme-color-violet-bold">Ce produit n'existe pas ou n'est plus disponible.</p>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm mt-2">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>
    <?php else: ?>

    <h2 class="theme-section-title"><?= e($produit->nom_categorie) ?></h2>

    <div class="p-4">

        <!-- Retour -->
        <div class="mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>

        <!-- SECTION PRINCIPALE : Photo + Infos -->
        <div class="theme-dashboard-bloc">
            <div class="row g-5 align-items-start">

                <!-- Galerie photos -->
                <div class="col-md-5">
                    <?php if (empty($images)): ?>
                        <div class="produit-detail-photo-vide">
                            <i class="bi bi-image fs-1 text-muted"></i>
                            <span class="text-muted small mt-2">Aucune photo disponible</span>
                        </div>
                    <?php else: ?>
                        <img id="photo-principale"
                             src="admin/<?= e($images[0]['url_image']) ?>"
                             alt="<?= e($produit->nom_produit) ?>"
                             class="produit-detail-photo-principale mb-3">
                        <?php if (count($images) > 1): ?>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($images as $k => $img): ?>
                                <img src="admin/<?= e($img['url_image']) ?>"
                                     alt=""
                                     class="galerie-img <?= $k === 0 ? 'active' : '' ?>">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Informations produit -->
                <div class="col-md-7">

                    <p class="text-muted small mb-1">
                        <i class="bi bi-tag me-1"></i><?= e($produit->nom_categorie) ?>
                    </p>

                    <h2 class="fw-bold mb-2 theme-produit-titre fs-2">
                        <?= e($produit->nom_produit) ?>
                    </h2>

                    <!-- Note moyenne -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="theme-etoiles-detail">
                            <?php if ($moyenne > 0):
                                $plein = floor($moyenne);
                                $demi = ($moyenne - $plein) >= 0.5;
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $plein): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php elseif ($i === $plein + 1 && $demi): ?>
                                        <i class="bi bi-star-half text-warning"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star text-warning"></i>
                                    <?php endif;
                                endfor;
                            else:
                                for ($i = 0; $i < 5; $i++): ?>
                                    <i class="bi bi-star theme-etoile-vide"></i>
                                <?php endfor;
                            endif; ?>
                        </div>
                        <?php if ($moyenne > 0): ?>
                            <span class="text-muted small">
                                <?= number_format($moyenne, 1, ',', '') ?>/5
                                (<?= $nbAvis ?> avis)
                            </span>
                        <?php else: ?>
                            <span class="text-muted small">Aucun avis pour le moment</span>
                        <?php endif; ?>
                    </div>

                    <!-- Prix + Stock + Actions -->
                    <div class="row g-0 align-items-stretch mb-4 theme-produit-actions-row">

                        <!-- Colonne gauche : prix, stock, badge promo -->
                        <div class="col-auto d-flex flex-column gap-2 justify-content-center pe-5">
                            <?php if ($prixPromo !== null): ?>
                                <div>
                                    <span class="text-muted text-decoration-line-through fs-5 me-1">
                                        <?= number_format((float)$produit->prix, 2, ',', ' ') ?> €
                                    </span>
                                    <span class="fs-2 fw-bold theme-color-rose">
                                        <?= $prixPromo ?> €
                                    </span>
                                </div>
                                <div>
                                    <span class="badge theme-badge-teal">-<?= round((float)$pourcent) ?>% de réduction</span>
                                </div>
                            <?php else: ?>
                                <span class="fs-2 fw-bold theme-color-rose">
                                    <?= number_format((float)$produit->prix, 2, ',', ' ') ?> €
                                </span>
                            <?php endif; ?>
                            <div>
                                <?php if ($produit->stock === 0): ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Rupture de stock
                                    </span>
                                <?php elseif ($produit->stock <= 5): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Plus que <?= $produit->stock ?> en stock
                                    </span>
                                <?php else: ?>
                                    <span class="badge theme-badge-teal">
                                        <i class="bi bi-check-circle me-1"></i>En stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Séparateur vertical -->
                        <div class="theme-produit-separateur"></div>

                        <!-- Colonne droite : boutons Acheter + Favoris -->
                        <div class="col d-flex flex-column gap-3 justify-content-center align-items-center ps-5">
                            <?php if ($produit->stock > 0): ?>
                                <button type="button" class="btn theme-btn-acheter" id="btn-acheter"
                                        data-id="<?= $produit->id_produit ?>">
                                    <i class="bi bi-cart-plus me-2"></i>Acheter
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn theme-btn-acheter" disabled>
                                    <i class="bi bi-cart-x me-2"></i>Indisponible
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn theme-btn-favoris" id="btn-favoris"
                                    data-id="<?= $produit->id_produit ?>">
                                <i class="bi bi-heart me-2"></i>Ajouter aux favoris
                            </button>
                        </div>

                    </div>

                    <hr class="theme-form-separator">

                    <!-- Description courte -->
                    <?php if ($produit->description): ?>
                        <p class="theme-produit-desc text-muted">
                            <?= nl2br(e($produit->description)) ?>
                        </p>
                    <?php endif; ?>

                </div>

            </div>
        </div>

        <!-- SECTION : Produits similaires -->
        <?php if (!empty($similaires)): ?>
            <div class="theme-dashboard-bloc">
                <h5 class="dash-section-title fw-bold mb-3 text-uppercase">
                    <i class="bi bi-grid me-2"></i>Produits similaires
                </h5>
                <div class="row g-3">
                    <?php foreach ($similaires as $s): ?>
                        <?php
                        $pDataS = $promotionsSimilaires[$s->id_produit] ?? null;
                        ?>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="carte-produit-maquette produit-card w-100">
                                <a href="index_.php?page=produit.php&id=<?= $s->id_produit ?>" class="text-decoration-none">
                                    <div class="img-container">
                                        <?php if ($s->photo_principale): ?>
                                            <img src="admin/<?= e($s->photo_principale) ?>" alt="<?= e($s->nom_produit) ?>">
                                        <?php else: ?>
                                            <div class="catalogue-img-vide">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <div class="carte-produit-corps">
                                    <div class="carte-produit-entete">
                                        <a href="index_.php?page=produit.php&id=<?= (int)$s->id_produit ?>" class="text-decoration-none">
                                            <h5 class="produit-titre"><?= e($s->nom_produit) ?></h5>
                                        </a>
                                        <button class="btn-wishlist-icon" data-id="<?= (int)$s->id_produit ?>" aria-label="Ajouter aux favoris"><i class="bi bi-heart"></i></button>
                                    </div>
                                    <p class="produit-desc"><?= e($s->nom_categorie) ?></p>

                                    <div class="carte-produit-pied">
                                        <div>
                                            <?php if ($pDataS): ?>
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                <span class="text-muted text-decoration-line-through small">
                                                    <?= number_format((float)str_replace(',', '.', (string)$s->prix), 2, ',', ' ') ?> €
                                                </span>
                                                    <span class="badge theme-badge-teal theme-badge-sm">
                                                    -<?= round((float)$pDataS->reduction_pourcent) ?>%
                                                </span>
                                                </div>
                                                <span class="prix-maquette">
                                                <?= number_format((float)$pDataS->prix_reduit, 2, ',', ' ') ?> €
                                            </span>
                                            <?php else: ?>
                                                <span class="prix-maquette">
                                                <?= number_format((float)str_replace(',', '.', (string)$s->prix), 2, ',', ' ') ?> €
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn-panier-pink-maquette" data-id="<?= $s->id_produit ?>" aria-label="Ajouter au panier">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION : Avis clients -->
        <div class="theme-dashboard-bloc">
            <h5 class="dash-section-title fw-bold mb-3 text-uppercase">
                <i class="bi bi-chat-quote me-2"></i>Avis clients
                <?php if ($nbAvis > 0): ?>
                    <span class="badge theme-badge-violet ms-2"><?= $nbAvis ?></span>
                <?php endif; ?>
            </h5>
            <?php if (empty($avis)): ?>
                <p class="text-muted text-center py-3">
                    <i class="bi bi-star d-block fs-3 mb-2"></i>
                    Soyez le premier à laisser un avis sur ce produit.
                </p>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($avis as $a): ?>
                        <div class="col-md-6">
                            <!-- Même rendu que les avis de l'accueil + date en bas -->
                            <div class="carte-avis h-100">
                                <?php if ($a->photo): ?>
                                    <img src="admin/<?= htmlspecialchars($a->photo, ENT_QUOTES, 'UTF-8') ?>" alt="Photo avis" class="avis-img">
                                <?php else: ?>
                                    <div class="avis-img-placeholder"><i class="bi bi-camera"></i></div>
                                <?php endif; ?>
                                <div class="avis-contenu">
                                    <div class="avis-etoiles">
                                        <?php for ($i = 0; $i < $a->note_etoiles; $i++): ?>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="avis-texte">"<?= htmlspecialchars($a->commentaire, ENT_QUOTES, 'UTF-8') ?>"</p>
                                    <p class="avis-auteur mb-1">
                                        <?= htmlspecialchars($a->prenom_client, ENT_QUOTES, 'UTF-8') ?>
                                        <?= htmlspecialchars(substr($a->nom_client, 0, 1), ENT_QUOTES, 'UTF-8') ?>.
                                    </p>
                                    <p class="text-muted small mb-0 text-end">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($a->date_avis)) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
    <?php endif; ?>
</div>
