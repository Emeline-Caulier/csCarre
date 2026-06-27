<?php
// Charge les catégories depuis la BDD pour la barre de navigation
$categoriesNav = [];
if (isset($cnx)) {
    try {
        $categoriesNav = (new CategorieDAO($cnx))->getAllCategories();
    } catch (Exception) {}
}
?>

<!-- Barre de catégories -->
<nav class="nav-categories">
    <a href="index_.php?page=accueil.php"
       class="btn-categorie <?= (($_GET['page'] ?? '') === 'accueil.php') ? 'actif' : '' ?>">
        Accueil
    </a>
    <a href="index_.php?page=nouveaute.php"
       class="btn-categorie <?= (($_GET['page'] ?? '') === 'nouveaute.php') ? 'actif' : '' ?>">
        <i class="me-1"></i>Nouveautés
    </a>
    <?php foreach ($categoriesNav as $cat): ?>
        <a href="index_.php?page=catalogue.php&id_cat=<?= (int)$cat->id_categorie ?>"
           class="btn-categorie <?= (($_GET['page'] ?? '') === 'catalogue.php' && ((int)($_GET['id_cat'] ?? 0)) === $cat->id_categorie) ? 'actif' : '' ?>">
            <?= e($cat->nom_categorie) ?>
        </a>
    <?php endforeach; ?>
</nav>
