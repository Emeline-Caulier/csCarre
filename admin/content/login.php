<?php

if(isset($_POST['submit']))
{
    extract($_POST, EXTR_OVERWRITE);
    
    if(!empty($email) && !empty($mot_de_passe))
    {
        $adminDAO = new AdminDAO($cnx);
        $adm = $adminDAO->getAdmin($email, $mot_de_passe);
        

        if ($adm !== null && $adm->id_admin !== -1) {
            $_SESSION['admin_id'] = $adm->id_admin;
            $_SESSION['admin_email'] = $adm->email_admin;
            $_SESSION['admin_nom'] = $adm->nom_admin;
            $_SESSION['admin_prenom'] = $adm->prenom_admin;
            $_SESSION['page'] = "accueil.php";
            
            header("Location: ./index_.php?page=accueil.php");
            exit;
        } else {
            $erreur = "Email ou mot de passe incorrect";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs";
    }
}

?>

<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email : </label>
            <input type="text" class="form-control" id="email" name="email" >
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe : </label>
            <input type="mot_de_passe" class="form-control" id="mot_de_passe" name="mot_de_passe">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Se connecter</button>
</form>
