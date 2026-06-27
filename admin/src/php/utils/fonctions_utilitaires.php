<?php
// Helpers PHP partagés entre les contrôleurs.

// Raccourci pour htmlspecialchars avec les options sécurisées par défaut.
// À utiliser dans toutes les vues : <?= e($variable)
function e(mixed $valeur): string
{
    return htmlspecialchars((string)($valeur ?? ''), ENT_QUOTES, 'UTF-8');
}

// Upload multi-photos produit. Retourne un tableau d'erreurs (vide si tout OK).
function uploadPhotos(int $idProduit, ProduitDAO $dao, string $dossier, string $prefixeUrl): array
{
    $erreurs = [];
    if (empty($_FILES['photos']['name'][0])) return $erreurs;

    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $codesErreur = [
        UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite serveur).',
        UPLOAD_ERR_FORM_SIZE  => 'Fichier trop volumineux (limite formulaire).',
        UPLOAD_ERR_PARTIAL => 'Upload incomplet.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire sur le disque.',
    ];

    foreach ($_FILES['photos']['tmp_name'] as $k => $tmp) {
        $code = $_FILES['photos']['error'][$k];
        if ($code === UPLOAD_ERR_NO_FILE) continue;

        if ($code !== UPLOAD_ERR_OK) {
            $erreurs[] = $_FILES['photos']['name'][$k] . ' : ' . ($codesErreur[$code] ?? "Erreur $code");
            continue;
        }

        $ext = strtolower(pathinfo($_FILES['photos']['name'][$k], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensionsAutorisees)) {
            $erreurs[] = $_FILES['photos']['name'][$k] . ' : format non autorisé.';
            continue;
        }

        // Nom de fichier unique pour éviter les collisions
        $fichier = 'prod_' . bin2hex(random_bytes(8)) . '.' . $ext;

        if (move_uploaded_file($tmp, $dossier . $fichier)) {
            $dao->addImage($idProduit, $prefixeUrl . $fichier);
        } else {
            $erreurs[] = $_FILES['photos']['name'][$k] . ' : échec du déplacement du fichier.';
        }
    }

    return $erreurs;
}

// Upload sécurisé d'une image unique (bannière, à propos).
// Vérifie le code d'erreur PHP, la taille (5 Mo par défaut), l'extension dans une
// whitelist, et le type MIME réel via getimagesize() pour rejeter les .php déguisés
// en .jpg. Renvoie le nom de fichier sauvegardé, ou null en cas d'échec.
function uploadImageSecurisee(array $fichier, string $dossier, string $prefixe = '', int $tailleMaxMo = 5): ?string
{
    if (empty($fichier['name']) || ($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($fichier['size'] > $tailleMaxMo * 1024 * 1024) {
        return null;
    }

    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $extensionsAutorisees, true)) {
        return null;
    }

    // getimagesize() échoue sur tout ce qui n'est pas une image (anti-shell .jpg déguisé)
    $info = @getimagesize($fichier['tmp_name']);
    if ($info === false) {
        return null;
    }
    $mimesAutorises = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($info['mime'], $mimesAutorises, true)) {
        return null;
    }

    // Nom de fichier imprévisible (l'extension d'origine est ignorée)
    $nomFichier = $prefixe . bin2hex(random_bytes(8)) . '.' . $ext;

    if (!move_uploaded_file($fichier['tmp_name'], $dossier . $nomFichier)) {
        return null;
    }

    return $nomFichier;
}

// Construit une URL de redirection vers une page admin, en ajoutant uniquement
// les paramètres non vides
function buildRedirectUrl(string $page, array $parametres = []): string
{
    $url = 'index_.php?page=' . $page;
    foreach ($parametres as $key => $value) {
        if ($value !== null && $value !== '') {
            $url .= '&' . $key . '=' . urlencode((string)$value);
        }
    }
    return $url;
}
