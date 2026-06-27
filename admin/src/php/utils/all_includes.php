<?php
$isAdmin = str_contains($_SERVER['REQUEST_URI'], 'admin');

if ($isAdmin) {
    $pathDb = 'src/php/db/db_pg_connect.php';
    $pathAutoloader = 'src/php/classes/Autoloader.class.php';
    if (!file_exists($pathDb)) {
        // Cas AJAX : l'appel vient du sous-dossier ajax/, on remonte d'un cran
        $pathDb = '../db/db_pg_connect.php';
        $pathAutoloader = '../classes/Autoloader.class.php';
    }
} else {
    $pathDb = 'admin/src/php/db/db_pg_connect.php';
    $pathAutoloader = 'admin/src/php/classes/Autoloader.class.php';
}

if (file_exists($pathDb) && file_exists($pathAutoloader)) {
    include $pathDb;
    include $pathAutoloader;
    Autoloader::register();
    $cnx = Connexion::getInstance($dsn, $user, $pass);
    require_once __DIR__ . '/fonctions_utilitaires.php';
} else {
    die("Impossible de charger les fichiers de configuration.");
}
