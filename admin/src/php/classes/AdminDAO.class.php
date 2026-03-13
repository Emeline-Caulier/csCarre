<?php

class AdminDAO
{
    private PDO $_cnx;

    public function __construct($cnx){
        $this->_cnx = $cnx;
    }

    public function getAdmin($email,$mot_de_passe)
    {
        $query = "select * from get_admin(:email,:mot_de_passe)";
        try{
            //Transaction + requête préparée
            $this->_cnx->beginTransaction();
            $stmt = $this->_cnx->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':mot_de_passe', $mot_de_passe);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->_cnx->commit();

            //Transaction :
            if (!$data)
            {
                return null;
            }
            if ((int)$data['id_admin'] === -1 && $data['prenom_admin'] === '' && $data['nom_admin'] === '' )
            {
                return null;
            }
            return new Admin(
                id_admin:(int)$data['id_admin'],
                nom_admin: $data['nom_admin'],
                prenom_admin:$data['prenom_admin'],
                email_admin:$data['email_admin']
            );

        }catch(PDOException $e){
            //Transaction
            $this->_cnx->rollBack();
            print $e->getMessage();
        }
    }
}
