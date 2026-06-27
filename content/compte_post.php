<?php
// Traitement des actions POST du compte client.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

$action = $_POST['action'] ?? '';

// Connexion / inscription
if ($action === 'submit_auth') {
    $email = trim($_POST['email_client'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';

    $cl = $clientDAO->getClientByEmail($email);

    // Un client existe déjà avec cet email : seule la connexion est possible.
    // Aucun fallback vers l'inscription pour éviter qu'un mauvais mot de passe
    // puisse tout de même connecter un client existant via le bloc d'inscription.
    if ($cl) {
        if (password_verify($password, $cl->mot_de_passe)) {
            $idSession = session_id();
            $_SESSION['client_id'] = $cl->id_client;
            $_SESSION['client_email'] = $cl->email_client;
            $_SESSION['client_nom'] = $cl->nom_client;
            $_SESSION['client_prenom'] = $cl->prenom_client;
            $listeEnvieDAO->lierClient($idSession, $cl->id_client);
            header("Location: index_.php?page=compte.php");
            exit;
        }
        header("Location: index_.php?page=compte.php&err=auth");
        exit;
    }

    // Aucun client avec cet email : on procède à l'inscription si tous les
    // champs requis (nom, prénom, email, mot de passe) sont bien fournis.
    $nom = trim($_POST['nom_client'] ?? '');
    $prenom = trim($_POST['prenom_client'] ?? '');
    if ($nom !== '' && $prenom !== '' && $email !== '' && $password !== '') {
        $passwordHache = password_hash($password, PASSWORD_DEFAULT);
        $clientDAO->addClient(
            $email,
            $passwordHache,
            $nom,
            $prenom,
            trim($_POST['rue'] ?? ''),
            trim($_POST['numero'] ?? ''),
            trim($_POST['code_postal'] ?? ''),
            trim($_POST['ville'] ?? ''),
            trim($_POST['pays'] ?? '')
        );
        $cl = $clientDAO->getClientByEmail($email);
        if ($cl) {
            $_SESSION['client_id'] = $cl->id_client;
            $_SESSION['client_email'] = $cl->email_client;
            $_SESSION['client_nom'] = $cl->nom_client;
            $_SESSION['client_prenom'] = $cl->prenom_client;
            header("Location: index_.php?page=compte.php");
            exit;
        }
    }

    header("Location: index_.php?page=compte.php&err=auth");
    exit;
}

// Modifier le profil
if ($action === 'update_profil' && isset($_SESSION['client_id'])) {
    $id = (int)$_SESSION['client_id'];
    $clientDAO->updateProfil($id,
        trim($_POST['nom_client'] ?? ''),
        trim($_POST['prenom_client'] ?? ''),
        trim($_POST['email_client'] ?? '')
    );
    $clientDAO->updateAdresse($id,
        trim($_POST['rue'] ?? ''),
        trim($_POST['numero'] ?? ''),
        trim($_POST['code_postal'] ?? ''),
        trim($_POST['ville'] ?? ''),
        trim($_POST['pays'] ?? '')
    );
    if (!empty($_POST['mot_de_passe'])) {
        $clientDAO->updatePassword($id, $_POST['mot_de_passe']);
    }
    $_SESSION['client_nom'] = trim($_POST['nom_client'] ?? '');
    $_SESSION['client_prenom'] = trim($_POST['prenom_client'] ?? '');
    $_SESSION['client_email'] = trim($_POST['email_client'] ?? '');
    header("Location: index_.php?page=compte.php&vue=profil&success=profil");
    exit;
}

// Soumettre un avis sur un produit d'une commande expédiée
// (un même produit peut être ré-évalué s'il provient d'une autre commande)
if ($action === 'submit_avis' && isset($_SESSION['client_id'])) {
    $idClient = (int)$_SESSION['client_id'];
    $idCommande = (int)($_POST['id_commande'] ?? 0);
    $idProduit = (int)($_POST['id_produit'] ?? 0);
    $note = (int)($_POST['note_etoiles'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    // Validation : note 1-5, commentaire non vide, droit d'évaluer ce couple (commande, produit)
    if ($note < 1 || $note > 5 || $commentaire === ''
        || $idCommande <= 0 || $idProduit <= 0
        || !$avisDAO->peutEvaluer($idClient, $idCommande, $idProduit)) {
        header("Location: index_.php?page=compte.php");
        exit;
    }

    // Upload photo facultatif (max 7 Mo)
    $cheminPhoto = null;
    if (!empty($_FILES['photo_avis']['tmp_name'])
        && $_FILES['photo_avis']['error'] === UPLOAD_ERR_OK) {

        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['photo_avis']['name'], PATHINFO_EXTENSION));
        $tailleMax = 5 * 1024 * 1024; // 5 Mo

        // Refus si trop volumineuse : on n'enregistre pas l'avis et on prévient l'utilisateur
        if ($_FILES['photo_avis']['size'] > $tailleMax) {
            header("Location: index_.php?page=compte.php&err=avis_photo_lourde");
            exit;
        }

        if (in_array($ext, $extensionsAutorisees)) {
            $fichier = 'avis_' . bin2hex(random_bytes(8)) . '.' . $ext;
            // Disque : on écrit depuis la racine du projet (chemin admin/ inclus)
            // BDD : on stocke le chemin relatif à admin/ (cohérent avec image_produit.url_image)
            if (move_uploaded_file($_FILES['photo_avis']['tmp_name'], 'admin/assets/images/avis/' . $fichier)) {
                $cheminPhoto = 'assets/images/avis/' . $fichier;
            }
        }
    }

    $avisDAO->addAvis($idClient, $idCommande, $idProduit, $note, $commentaire, $cheminPhoto);
    header("Location: index_.php?page=compte.php&success=avis");
    exit;
}

// Envoyer un message
if ($action === 'contact' && isset($_SESSION['client_id'])) {
    $sujet = trim($_POST['sujet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $numCmd = !empty($_POST['num_commande']) ? (int)$_POST['num_commande'] : null;
    if ($sujet !== '' && $contenu !== '') {
        $messageDAO->sendMessage(
            (int)$_SESSION['client_id'],
            $_SESSION['client_prenom'] . ' ' . $_SESSION['client_nom'],
            $_SESSION['client_email'],
            $numCmd,
            $sujet,
            $contenu
        );
    }
    header("Location: index_.php?page=compte.php&success=contact");
    exit;
}
