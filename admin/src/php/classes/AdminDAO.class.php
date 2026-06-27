<?php
// Accès aux données de la table admin via la fonction SQL get_admin()
class AdminDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    public function getAdminByEmail($email) {
        try {
            $query = "SELECT * FROM admin WHERE email_admin = :email";
            $res = $this->_cnx->prepare($query);
            $res->bindValue(':email', $email);
            $res->execute();
            return $res->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
