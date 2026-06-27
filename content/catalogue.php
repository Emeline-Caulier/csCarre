<?php
$categorieDAO = new CategorieDAO($cnx);
$produitDAO = new ProduitDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);

$id_cat = isset($_GET['id_cat']) ? (int)$_GET['id_cat'] : 0;
$categorie = $id_cat > 0 ? $categorieDAO->getCategorieById($id_cat) : null;
$produits = $categorie ? $produitDAO->getProduitsByCategorie($id_cat) : $produitDAO->getAllProduits();
$titre = $categorie ? $categorie->nom_categorie : 'Tous les produits';
$promotions = $promotionDAO->getAllIndexedByProduit();


$tri = in_array($_GET['tri'] ?? '', ['prix_asc', 'prix_desc']) ? $_GET['tri'] : '';
$filtrePromo = ($_GET['filtre'] ?? '') === 'promo';
$filtreNouveau = ($_GET['filtre'] ?? '') === 'nouveau'; // Ajout pour la barre


if ($filtrePromo) {
    $produits = array_values(array_filter($produits, fn($p) => isset($promotions[$p->id_produit])));
}
if ($filtreNouveau) {
    $produits = array_values(array_filter($produits, fn($p) => (bool)$p->est_nouveau));
}


if ($tri !== '') {
    $prixEffectif = function($p) use ($promotions) {
        if (isset($promotions[$p->id_produit])) {
            return (float)$promotions[$p->id_produit]->prix_reduit;
        }

        $prixNettoye = str_replace([' ', '€', ','], ['', '', '.'], (string)$p->prix);
        return (float)$prixNettoye;
    };

    usort($produits, $tri === 'prix_asc'
            ? fn($a, $b) => $prixEffectif($a) <=> $prixEffectif($b)
            : fn($a, $b) => $prixEffectif($b) <=> $prixEffectif($a)
    );
}

$urlBase = 'index_.php?page=catalogue.php' . ($id_cat > 0 ? '&id_cat=' . $id_cat : '');
?>

<div class="theme-section">
    <h2 class="theme-section-title"><?= htmlspecialchars($titre) ?></h2>

    <div class="p-4">
        <?php include 'content/vues/barre_tri_filtre.php'; ?>
        <?php include 'content/vues/grille_produits.php'; ?>
    </div>
</div>
