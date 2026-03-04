<?php

$isAdmin = str_contains(__DIR__, 'admin');
if ($isAdmin) {
    $pathDb = 'src/php/db/db_pg_connect.php';
    $pathAutoloader = 'src/php/classes/Autoloader.class.php';
} else {
    $pathDb = 'admin/src/php/db/db_pg_connect.php';
    $pathAutoloader = 'admin/src/php/classes/Autoloader.class.php';
}

if (file_exists($pathDb) && file_exists($pathAutoloader)) {
    include $pathDb;
    include $pathAutoloader;

    Autoloader::register(); //:: manière d'appeller une méthode statique

    $cnx = Connexion::getInstance($dsn, $user, $pass);
} else {
    die("Impossible de charger les fichiers");
}
