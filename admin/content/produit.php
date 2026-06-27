<?php
// Contrôleur produit : gère le routage entre les vues et traite les actions CRUD
require "src/php/utils/check_connexion.php";

// --- Initialisation ---

$produitDAO = new ProduitDAO($cnx);
$categorieDAO = new CategorieDAO($cnx);
$promotionDAO = new PromotionDAO($cnx);

// Chemins de stockage des images
$dossierPhotos = dirname(__DIR__) . '/assets/images/produits/';
$urlPhotos = 'assets/images/produits/';

if (!is_dir($dossierPhotos)) {
    mkdir($dossierPhotos, 0755, true);
}

// Messages de notification (succès / erreur), aussi peuplés depuis les
// paramètres GET après un redirect (pattern PRG).
$message = [];
if (isset($_GET['warn']) && $_GET['warn'] === 'photos') {
    $message = ['warning', 'Produit sauvegardé mais certaines photos n\'ont pas pu être uploadées.'];
} elseif (isset($_GET['err'])) {
    $message = ['danger', match($_GET['err']) {
        'add'  => "Erreur lors de l'ajout du produit. Vérifiez les champs obligatoires.",
        'edit' => 'Erreur lors de la modification. Vérifiez les champs obligatoires.',
        default => 'Une erreur est survenue.',
    }];
}

// --- Suppressions (requêtes GET) ---

// Supprimer une image d'un produit
if (isset($_GET['delete_img'], $_GET['edit'])) {
    $produitDAO->deleteImage((int)$_GET['delete_img']);
    header("Location: index_.php?page=produit.php&edit={$_GET['edit']}&id_cat=" . (int)($_GET['id_cat'] ?? 0));
    exit;
}

// Supprimer un produit complet
if (isset($_GET['delete'], $_GET['id_cat'])) {
    $produitDAO->deleteProduit((int)$_GET['delete']);
    header("Location: index_.php?page=produit.php&id_cat={$_GET['id_cat']}");
    exit;
}

// Supprimer une catégorie (seulement si elle ne contient aucun produit)
if (isset($_GET['cat_delete'])) {
    $idASupprimer = (int)$_GET['cat_delete'];
    if ($idASupprimer > 0) {
        try {
            $stmt = $cnx->prepare("SELECT COUNT(*) FROM produit WHERE id_categorie = :id");
            $stmt->bindValue(':id', $idASupprimer, PDO::PARAM_INT);
            $stmt->execute();
            if ((int)$stmt->fetchColumn() === 0) {
                $categorieDAO->deleteCategorie($idASupprimer);
            }
        } catch (PDOException) {}
    }
    header("Location: index_.php?page=produit.php");
    exit;
}

// --- Formulaires (requêtes POST) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Ajouter une catégorie
    if ($_POST['action'] === 'cat_add') {
        $nom = trim($_POST['nom_categorie'] ?? '');
        if ($nom !== '') {
            $categorieDAO->addCategorie($nom);
        }
        header("Location: index_.php?page=produit.php");
        exit;
    }

    // Modifier le nom d'une catégorie
    if ($_POST['action'] === 'cat_edit') {
        $idCategorie = (int)($_POST['id_categorie'] ?? 0);
        $nom = trim($_POST['nom_categorie'] ?? '');
        if ($idCategorie > 0 && $nom !== '') {
            $categorieDAO->updateCategorie($idCategorie, $nom);
        }
        header("Location: index_.php?page=produit.php");
        exit;
    }

    // Données communes aux actions add / edit produit
    $idCategorie = (int)($_POST['id_categorie'] ?? 0);
    $nom = trim($_POST['nom_produit'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $prix = trim($_POST['prix'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $estNouveau = isset($_POST['est_nouveau']);

    // Ajouter un produit
    if ($_POST['action'] === 'add') {
        if ($idCategorie > 0 && $nom !== '' && $prix !== '') {
            $idProduit = $produitDAO->addProduit($idCategorie, $nom, $desc, $prix, $stock);
            if ($idProduit) {
                if ($estNouveau) {
                    $produitDAO->setEstNouveau($idProduit, true);
                }
                $erreurs = uploadPhotos($idProduit, $produitDAO, $dossierPhotos, $urlPhotos);
                if (!empty($erreurs)) {
                    header("Location: index_.php?page=produit.php&edit=$idProduit&id_cat=$idCategorie&warn=photos");
                } else {
                    header("Location: index_.php?page=produit.php&id_cat=$idCategorie");
                }
                exit;
            }
        }
        header("Location: index_.php?page=produit.php&add&id_cat=$idCategorie&err=add");
        exit;
    }

    // Modifier un produit
    if ($_POST['action'] === 'edit') {
        $idProduit = (int)($_POST['id_produit'] ?? 0);
        if ($idProduit > 0 && $idCategorie > 0 && $nom !== '') {
            $nbModifications = $produitDAO->updateProduit($idProduit, $idCategorie, $nom, $desc, $prix, $stock, $estNouveau);
            if ($nbModifications === 0) {
                // L'UPDATE a échoué (contrainte unique, id introuvable, etc.)
                header("Location: index_.php?page=produit.php&edit=$idProduit&id_cat=$idCategorie&err=unique");
                exit;
            }
            $erreurs = uploadPhotos($idProduit, $produitDAO, $dossierPhotos, $urlPhotos);
            if (!empty($erreurs)) {
                header("Location: index_.php?page=produit.php&edit=$idProduit&id_cat=$idCategorie&warn=photos");
            } else {
                header("Location: index_.php?page=produit.php&id_cat=$idCategorie");
            }
            exit;
        }
        header("Location: index_.php?page=produit.php&edit=$idProduit&id_cat=$idCategorie&err=edit");
        exit;
    }
}

// --- Routage des vues ---

// Détermination de la vue à afficher selon les paramètres d'URL
$vue = 'categories';
$idCategorie = (int)($_GET['id_cat'] ?? 0);
$idProduit = 0;

if (isset($_GET['add'])) { $vue = 'form_add'; }
elseif (isset($_GET['edit'])) { $vue = 'form_edit'; $idProduit = (int)$_GET['edit']; }
elseif (isset($_GET['detail'])) { $vue = 'detail'; $idProduit = (int)$_GET['detail']; }
elseif ($idCategorie > 0) { $vue = 'grille'; }

// Chargement des données selon la vue active
$categories = $categorieDAO->getAllCategories();
$produits = ($vue === 'grille') ? $produitDAO->getProduitsByCategorie($idCategorie) : [];
$promotionsParProduit = ($vue === 'grille') ? $promotionDAO->getAllIndexedByProduit() : [];
$produit = in_array($vue, ['form_edit', 'detail']) ? $produitDAO->getProduitById($idProduit) : null;
$images = ($produit && in_array($vue, ['form_edit', 'detail'])) ? $produitDAO->getImagesByProduit($idProduit) : [];

// Statistiques de stock (chargées uniquement pour la vue inventaire)
$countBycat = $produitDAO->getCountByCategorie();
$stockByCat = ($vue === 'categories') ? $produitDAO->getStockStatsByCategorie() : [];

// Filtre de stock actif (en_stock / surveiller / rupture)
$filtreStock = (isset($_GET['stock']) && in_array($_GET['stock'], ['en_stock', 'surveiller', 'rupture']))
    ? $_GET['stock'] : '';

if ($vue === 'grille' && $filtreStock !== '') {
    $produits = array_values(array_filter($produits, fn($p) => match($filtreStock) {
        'rupture' => $p->stock === 0,
        'surveiller'=> $p->stock > 0 && $p->stock <= 5,
        'en_stock' => $p->stock > 5,
        default  => true,
    }));
}

if ($vue === 'categories' && $filtreStock !== '') {
    $categories = array_values(array_filter($categories, function ($cat) use ($stockByCat, $filtreStock) {
        $niveauStock = $stockByCat[$cat->id_categorie] ?? ['rupture' => 0, 'surveiller' => 0, 'en_stock' => 0];
        return match($filtreStock) {
            'rupture' => $niveauStock['rupture'] > 0,
            'surveiller'=> $niveauStock['surveiller'] > 0,
            'en_stock' => $niveauStock['en_stock'] > 0,
            default => true,
        };
    }));
}

// Catégorie actuellement sélectionnée (pour la vue grille)
$categorieCourante = null;
foreach ($categories as $cat) {
    if ($cat->id_categorie === $idCategorie) { $categorieCourante = $cat; break; }
}

// --- Affichage ---

$vuesDir = 'vues/produit/';

if (!empty($message)): ?>
    <div class="alert alert-<?= $message[0] ?> alert-dismissible fade show mx-4 mt-3">
        <?= $message[1] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif;

match($vue) {
    'categories' => include $vuesDir . 'categories.php',
    'grille' => include $vuesDir . 'grille.php',
    'form_add', 'form_edit' => include $vuesDir . 'formulaire.php',
    'detail' => include $vuesDir . 'detail.php',
    default => include $vuesDir . 'categories.php',
};
