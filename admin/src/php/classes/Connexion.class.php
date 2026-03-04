<?php

class Connexion
{
    // méthode statique dont la classe ne nécesite pas d'instanciation
    public static function getInstance($dsn, $user, $pass)
    {
        try {
            $pdo = new PDO($dsn, $user, $pass);
            var_dump($pdo); // affiche le contenu d'un objet
            return $pdo;
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
        }
    }
}