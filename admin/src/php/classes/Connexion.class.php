<?php

class Connexion
{
    public static function getInstance(string $dsn, string $user, string $pass): PDO
    {
        try {
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new RuntimeException('Connexion à la base de données impossible');
        }
    }
}
