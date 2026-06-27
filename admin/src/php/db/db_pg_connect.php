<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'acrylia';
$port = getenv('DB_PORT') ?: '5432';
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

if (!$user || !$pass) {
    throw new RuntimeException('DB_USER et DB_PASS doivent être définis (variables d\'environnement)');
}

$dsn = "pgsql:host=$host;dbname=$dbname;port=$port";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    throw new RuntimeException('Connexion à la base de données impossible');
}
