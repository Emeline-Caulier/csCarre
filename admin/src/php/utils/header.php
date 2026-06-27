<!-- Barre supérieure : logo + recherche + icônes utilisateur -->
<!-- $urlBase est défini dans index_.php ('' côté admin, 'admin/' côté public) -->
<div class="header-top">

    <a href="index_.php?page=accueil.php" class="header-logo">
        <img src="<?= $urlBase ?>assets/images/logo_acrylia.jpg" alt="Acrylia">
    </a>

    <?php if (isset($_SESSION['admin_id'])): ?>
    <form class="header-search" method="get" action="index_.php" role="search" autocomplete="off">
        <input type="hidden" name="page" value="recherche.php">
        <i class="bi bi-search search-icon"></i>
        <input type="search" name="q" id="admin-search-input"
               placeholder="Client, produit, n° commande…"
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
               autocomplete="off">
        <div id="admin-search-dropdown" class="search-dropdown" hidden></div>
    </form>
    <?php else: ?>
    <form class="header-search" method="get" action="index_.php" role="search" autocomplete="off">
        <input type="hidden" name="page" value="recherche.php">
        <i class="bi bi-search search-icon"></i>
        <input type="search" name="q" id="client-search-input"
               placeholder="Que recherchez-vous ?"
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
               autocomplete="off">
        <div id="client-search-dropdown" class="search-dropdown" hidden></div>
    </form>
    <?php endif; ?>

    <div class="header-icons">
        <!-- 1. Le bouton OVALE (Administration ou Voir le site) -->
        <?php if (isset($_SESSION['admin_id'])): ?>
            <?php if (isset($urlBase) && $urlBase === ''): ?>
                <!-- Côté admin : basculer vers le site client -->
                <a href="../index_.php?page=accueil.php" class="theme-btn-pill" title="Voir le site côté client">
                    <i class="bi bi-shop"></i> Voir le site
                </a>
            <?php else: ?>
                <!-- Côté client en mode admin : retour à l'administration -->
                <a href="admin/index_.php" class="theme-btn-pill" title="Retour à l'administration">
                    <i class="bi bi-shield-lock"></i> Administration
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- 2. Langue (FR) et Menu Burger -->
        <?php if (!isset($urlBase) || $urlBase !== ''): ?>
            <span class="lang">FR</span>
        <?php endif; ?>

        <button class="nav-burger" id="navBurger" aria-label="Ouvrir le menu" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>

        <!-- 3. Icônes de compte client -->
        <?php if (!isset($urlBase) || $urlBase !== ''): ?>
            <?php if (!isset($_SESSION['client_id'])): ?>
                <a href="index_.php?page=compte.php" class="header-icon-btn" title="Mon compte">
                    <i class="bi bi-person"></i>
                </a>
            <?php else: ?>
                <a href="index_.php?page=compte.php" class="header-icon-btn" title="<?= e($_SESSION['client_prenom']) ?>">
                    <i class="bi bi-person-fill"></i>
                </a>
                <a href="index_.php?page=disconnect.php" class="header-icon-btn" title="Se déconnecter">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- 4. Déconnexion Admin OU Icônes Panier/Favoris (placés à droite) -->
        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="./index_.php?page=disconnect.php" class="header-icon-btn" title="Déconnexion">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        <?php elseif (isset($urlBase) && $urlBase === ''): ?>
            <!-- Admin non connecté : bouton connexion admin -->
            <a href="./index_.php?page=login.php" class="header-icon-btn" title="Connexion admin">
                <i class="bi bi-shield-lock"></i>
            </a>
        <?php else: ?>
            <!-- Site client : favoris + panier -->
            <a href="index_.php?page=favoris.php" class="header-icon-btn" title="Mes favoris">
                <i class="bi bi-heart"></i>
            </a>
            <a href="index_.php?page=panier.php" class="header-icon-btn" title="Mon panier">
                <i class="bi bi-cart3"></i>
            </a>
        <?php endif; ?>

    </div>
</div>
