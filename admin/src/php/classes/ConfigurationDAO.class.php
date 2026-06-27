<?php
declare(strict_types=1);

class ConfigurationDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // Retourne un tableau d'objets Configuration
    public function getAll(): array
    {
        try {
            $stmt = $this->_cnx->query("SELECT cle, valeur FROM configuration");
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = new Configuration($row['cle'], $row['valeur']);
            }
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Met à jour une valeur
    public function update(string $cle, string $valeur): bool
    {
        try {
            $stmt = $this->_cnx->prepare("UPDATE configuration SET valeur = :valeur WHERE cle = :cle");
            $stmt->bindValue(':valeur', $valeur);
            $stmt->bindValue(':cle', $cle);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}