<?php

class CategorieDAO
{
    private PDO $_cnx;

    public function __construct(PDO $_cnx)
    {
        $this->_cnx = $_cnx;
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categorie";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($d) {
                return new Categorie(
                    id_categorie: (int)$d['id_categorie'],
                    nom_categorie: (string)$d['nom_categorie']
                );
            }, $data);
        } catch (PDOException $e) {
            print $e->getMessage();
            return null;
        }
    }

    public function getCategorieById(int $id_categorie)
    {
        //A définir
    }

    public function addCategorie(string $nom_categorie)
    {
        $query = "select ajout_categorie(:nom_categorie) as retour";
        try {
            $stmt = $this->_cnx->prepare($query);
            $stmt->bindParam(":nom_categorie", $nom_categorie);
            $stmt->execute();
            $retour = $stmt->fetchColumn(0);
            return $retour;

        } catch (PDOException $e) {
            print $e->getMessage();
            return null;
        }
    }
}
