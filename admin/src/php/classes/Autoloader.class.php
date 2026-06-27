<?php
// Autoloader : charge automatiquement NomClasse.class.php à la demande,
// via spl_autoload_register. Évite d'avoir à require chaque classe à la main.
class Autoloader {

    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class) {
        $file = __DIR__ . '/' . $class . '.class.php';
        if (file_exists($file)) {
            require_once $file;
        }
        else{
            print "Classe absente";
        }
    }
}
