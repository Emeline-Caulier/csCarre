<?php
$produitDAO = new ProduitDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);

$q = trim($_GET['q'] ?? '');
$produits = $q !== '' ? $produitDAO->rechercherProduits($q) : [];
$promotions = $promotionDAO->getAllIndexedByProduit();
$tri = in_array($_GET['tri'] ?? '', ['prix_asc', 'prix_desc']) ? $_GET['tri'] : '';
$filtrePromo = ($_GET['filtre'] ?? '') === 'promo';
$filtreNouveau = ($_GET['filtre'] ?? '') === 'nouveau';

if ($filtrePromo) {
    $produits = array_values(array_filter($produits, fn($p) => isset($promotions[$p->id_produit])));
}
if ($filtreNouveau) {
    $produits = array_values(array_filter($produits, fn($p) => (bool)$p->est_nouveau));
}

if ($tri !== '') {
    $prixEffectif = fn($p) => isset($promotions[$p->id_produit])
        ? (float)$promotions[$p->id_produit]->prix_reduit
        : (float)$p->prix;
    usort($produits, $tri === 'prix_asc'
        ? fn($a, $b) => $prixEffectif($a) <=> $prixEffectif($b)
        : fn($a, $b) => $prixEffectif($b) <=> $prixEffectif($a)
    );
}

$urlBase = 'index_.php?page=recherche.php' . ($q !== '' ? '&q=' . urlencode($q) : '');
?>

<div class="theme-section">
    <h2 class="theme-section-title">
        <?= $q !== '' ? 'Résultats pour « ' . htmlspecialchars($q) . ' »' : 'Recherche' ?>
    </h2>
    <?php if ($q !== ''): ?>
        <div class="text-end pe-2 pt-1">
            <span class="badge theme-badge-teal"><?= count($produits) ?> produit<?= count($produits) > 1 ? 's' : '' ?></span>
        </div>
    <?php endif; ?>

    <div class="p-4">
        <?php if ($q === ''): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                <p class="fw-semibold">Saisissez un terme dans la barre de recherche.</p>
            </div>
        <?php else: ?>
            <?php include 'content/vues/barre_tri_filtre.php'; ?>
            <?php include 'content/vues/grille_produits.php'; ?>
        <?php endif; ?>
    </div>
</div>
