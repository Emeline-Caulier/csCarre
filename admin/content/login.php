<?php
$erreur = '';

if (isset($_POST['submit'])) {
    // Assignations explicites (jamais extract() : permettrait d'écraser n'importe quelle variable)
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if ($email !== '' && $mot_de_passe !== '') {
        $adminDAO = new AdminDAO($cnx);
        $adm = $adminDAO->getAdminByEmail($email);

        if ($adm && password_verify($mot_de_passe, $adm->mot_de_passe)) {
            // Régénère l'ID de session pour prévenir la session fixation
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $adm->id_admin;
            $_SESSION['admin_email'] = $adm->email_admin;
            $_SESSION['admin_nom'] = $adm->nom_admin;
            $_SESSION['admin_prenom'] = $adm->prenom_admin;
            $_SESSION['page'] = "accueil.php";

            header("Location: index_.php?page=accueil.php");
            exit;
        }
        $erreur = "Email ou mot de passe incorrect";
    } else {
        $erreur = "Veuillez remplir tous les champs";
    }
}
?>

<div class="theme-section">
    <h2 class="theme-section-title">Administration</h2>
    <div class="p-4 theme-dashboard-bloc theme-login-wrapper">

        <p class="theme-login-title"><i class="bi bi-shield-lock me-2"></i>Connexion administrateur</p>

        <?php if ($erreur !== ''): ?>
            <div class="theme-login-error"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                       title="Veuillez saisir une adresse email valide (ex : nom@exemple.fr).">
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe">
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary theme-btn-fuzzy py-2" name="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>
            </div>
        </form>

    </div>
</div>
