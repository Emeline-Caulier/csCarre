<?php
$configDAO = new ConfigurationDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);
$avisDAO = new AvisDAO($cnx);

$objetsConfig = $configDAO->getAll();
$config = [];
foreach ($objetsConfig as $c) {
    $config[$c->cle] = $c->valeur;
}

$promotions = $promotionDAO->getPromotionsAccueil(10);
$avis = $avisDAO->getDerniersAvisApprouves(2);
?>

<div class="accueil-wrapper">

    <!-- SECTION 1 : BANNIERE -->
    <div class="hero-banniere container-fluid p-0 mb-5 position-relative">
        <img src="admin/<?= htmlspecialchars($config['accueil_banniere_img'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="Nouvelle Collection" class="w-100 hero-img">
        <div class="hero-bouton-overlay">
            <a href="index_.php?page=nouveaute.php" class="btn-cyan-maquette" class="btn-cyan-maquette">
                <?= htmlspecialchars($config['accueil_banniere_bouton'] ?? 'Découvrir la nouvelle collection', ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    </div>

    <!-- SECTION 2 : PROMOTIONS DU MOMENT -->
    <div class="section-promotions">
        <h3 class="titre-rubrique-maquette">Promotions du moment</h3>
        <div class="carrousel-wrapper">
            <button class="carrousel-btn" type="button" data-bs-target="#carrouselPromotions" data-bs-slide="prev" aria-label="Précédent">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div id="carrouselPromotions" class="carousel slide flex-grow-1">
                <div class="carousel-inner">
                    <?php
                    // 1 produit par slide sur mobile (≤768px), 5 sur desktop
                    $tailleChunk = 5;
                    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone|iPad/i', $_SERVER['HTTP_USER_AGENT'])) {
                        $tailleChunk = 1;
                    }
                    foreach (array_chunk($promotions, $tailleChunk) as $i => $groupe): ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <div class="carrousel-slide-inner">
                                <?php foreach ($groupe as $promo): ?>
                                    <div class="carte-produit-maquette produit-card">
                                        <a href="index_.php?page=produit.php&id=<?= $promo->id_produit ?>" class="text-decoration-none">
                                            <div class="img-container">
                                                <img src="admin/<?= htmlspecialchars($promo->image_url ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($promo->nom_produit, ENT_QUOTES, 'UTF-8') ?>">
                                            </div>
                                        </a>
                                        <div class="carte-produit-corps">
                                            <div class="carte-produit-entete">
                                                <a href="index_.php?page=produit.php&id=<?= $promo->id_produit ?>" class="text-decoration-none">
                                                    <h5 class="produit-titre"><?= htmlspecialchars($promo->nom_produit, ENT_QUOTES, 'UTF-8') ?></h5>
                                                </a>
                                                <button class="btn-wishlist-icon" data-id="<?= $promo->id_produit ?>" aria-label="Ajouter aux favoris"><i class="bi bi-heart"></i></button>
                                            </div>
                                            <p class="produit-desc"><?= htmlspecialchars($promo->nom_categorie ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                                            <div class="carte-produit-pied">
                                                <div>
                                                    <div class="d-flex align-items-center gap-1 mb-1">
                                                       <span class="text-muted text-decoration-line-through small">
                                                           <?= number_format((float)$promo->prix_origine, 2, ',', ' ') ?> €
                                                       </span>
                                                        <span class="badge theme-badge-teal theme-badge-sm">
                                                            -<?= round((float)$promo->reduction_pourcent) ?>%
                                                        </span>
                                                    </div>
                                                    <span class="prix-maquette">
                                                        <?= number_format((float)$promo->prix_reduit, 2, ',', ' ') ?> €
                                                    </span>
                                                </div>
                                                <button class="btn-panier-pink-maquette" data-id="<?= $promo->id_produit ?>" aria-label="Ajouter au panier">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="carrousel-btn" type="button" data-bs-target="#carrouselPromotions" data-bs-slide="next" aria-label="Suivant">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- SECTION 3 : À PROPOS -->
    <div class="section-apropos">
        <h3 class="titre-rubrique-violet">A propos de l'atelier</h3>
        <div class="apropos-bloc">
            <div class="apropos-img-container">
                <img src="admin/<?= htmlspecialchars($config['accueil_apropos_img'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="L'atelier" class="apropos-img">
            </div>
            <div class="apropos-texte-container">
                <div class="apropos-bloc-contenu">
                    <p class="apropos-texte mb-3"><?= nl2br(htmlspecialchars($config['accueil_apropos_texte'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
                    <a href="index_.php?page=en_savoir_plus.php" class="btn-cyan-fluo">
                        <?= htmlspecialchars($config['accueil_apropos_bouton'] ?? 'En savoir plus', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4 : LES AVIS -->
    <div class="section-avis">
        <h3 class="titre-rubrique-violet">Les avis</h3>
        <div class="grille-avis">
            <?php foreach ($avis as $a): ?>
                <div class="carte-avis">
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
                        <p class="avis-auteur"><?= htmlspecialchars($a->prenom_client, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars(substr($a->nom_client, 0, 1), ENT_QUOTES, 'UTF-8') ?>.</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- SECTION 5 : BADGES DE RÉASSURANCE -->
<div class="reassurance-bloc">
    <img src="./admin/assets/images/badges/artisan_certifie.png" alt="Artisan certifié">
    <img src="./admin/assets/images/badges/garantie_qualite.png" alt="Garantie qualité">
    <img src="./admin/assets/images/badges/livraison_gratuite.png" alt="Livraison gratuite">
</div>
