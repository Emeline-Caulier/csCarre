<?php

class Connexion
{
    public static function getInstance($dsn, $user, $pass)
    {
        try {
            $pdo = new PDO($dsn, $user, $pass);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
        }
    }
}