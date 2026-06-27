<?php

// Accès aux données de la table categorie
class CategorieDAO
{
    private PDO $_cnx;

    public function __construct(PDO $_cnx)
    {
        $this->_cnx = $_cnx;
    }

    public function getAllCategories(): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM categorie ORDER BY id_categorie ASC");
            $stmt->execute();
            return array_map(fn($d) => new Categorie(
                id_categorie: (int)$d['id_categorie'],
                nom_categorie: (string)$d['nom_categorie']
            ), $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getCategorieById(int $id_categorie): ?Categorie
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM categorie WHERE id_categorie = :id");
            $stmt->bindValue(':id', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
            $d = $stmt->fetch(PDO::FETCH_ASSOC);
            return $d ? new Categorie(id_categorie: (int)$d['id_categorie'], nom_categorie: $d['nom_categorie']) : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function addCategorie(string $nom_categorie): mixed
    {
        // Passe par la fonction PL/pgSQL ajout_categorie() avec transaction
        try {
            $this->_cnx->beginTransaction();
            $stmt = $this->_cnx->prepare("SELECT ajout_categorie(:nom_categorie) AS retour");
            $stmt->bindValue(':nom_categorie', $nom_categorie);
            $stmt->execute();
            $retour = $stmt->fetchColumn();
            $this->_cnx->commit();
            return $retour;
        } catch (PDOException $e) {
            $this->_cnx->rollBack();
            error_log($e->getMessage());
            return null;
        }
    }

    public function updateCategorie(int $id_categorie, string $nom_categorie): int
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_categorie(:id, :nom) AS retour");
            $stmt->bindValue(':id', $id_categorie, PDO::PARAM_INT);
            $stmt->bindValue(':nom', $nom_categorie);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function deleteCategorie(int $id_categorie): int
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT delete_categorie(:id) AS retour");
            $stmt->bindValue(':id', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
}
