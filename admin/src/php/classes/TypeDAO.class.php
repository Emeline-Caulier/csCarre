<?php

class TypeDAO
{
    private PDO $_cnx;

    public function __construct(PDO $_cnx)
    {
        $this->_cnx = $_cnx;
    }

    public function getAllTypes()
    {
        $sql = "SELECT * FROM type";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($d) {
                return new Type(
                    id_type: (int)$d['id_type'],
                    nom_type: (string)$d['nom_type']
                );
            }, $data);
        } catch (PDOException $e) {
            print $e->getMessage();
            return null;
        }
    }

    public function getTypeById(int $id_type)
    {
        //A définir
    }

    public function addType(string $nom_type)
    {

        $query = "select ajout_type(:nom_type) as retour";
        try {
            $stmt = $this->_cnx->prepare($query);
            $stmt->bindParam(":nom_type", $nom_type);
            $stmt->execute();
            $retour = $stmt->fetchColumn(0);
            return $retour;

        } catch (PDOException $e) {
            print $e->getMessage();
            return null;
        }
    }
}


