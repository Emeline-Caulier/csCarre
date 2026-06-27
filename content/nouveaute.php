<?php
$produitDAO = new ProduitDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);
$configDAO = new ConfigurationDAO($cnx);

$produits = $produitDAO->getNouvelleCollection();
$promotions = $promotionDAO->getAllIndexedByProduit();

$objetsConfig = $configDAO->getAll();
$config = [];
foreach ($objetsConfig as $c) {
    $config[$c->cle] = $c->valeur;
}
$titre = htmlspecialchars($config['nom_nouvelle_collection'] ?? 'Nouveautés');

$tri = in_array($_GET['tri'] ?? '', ['prix_asc', 'prix_desc']) ? $_GET['tri'] : '';
$filtrePromo = ($_GET['filtre'] ?? '') === 'promo';

if ($filtrePromo) {
    $produits = array_values(array_filter($produits, fn($p) => isset($promotions[$p->id_produit])));
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

$urlBase = 'index_.php?page=nouveaute.php';
?>

<div class="theme-section">
    <h2 class="theme-section-title"><i class="me-2"></i><?= $titre ?></h2>

    <div class="p-4">
        <?php include 'content/vues/barre_tri_filtre.php'; ?>
        <?php include 'content/vues/grille_produits.php'; ?>
    </div>
</div>
