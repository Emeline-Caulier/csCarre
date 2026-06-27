<?php

// Accès aux données de la table client et de la vue v_donnees_client
class ClientDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // Crée un client via la fonction SQL stockée ajout_client()
    public function addClient(string $email, string $password, string $nom, string $prenom,
                              string $rue, string $numero, string $code_postal, string $ville, string $pays): mixed
    {
        $query = "SELECT ajout_client(:email, :password, :nom, :prenom, :rue, :numero, :cp, :ville, :pays) AS retour";
        try {
            $this->_cnx->beginTransaction();
            $stmt = $this->_cnx->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':nom', $nom);
            $stmt->bindValue(':prenom', $prenom);
            $stmt->bindValue(':rue', $rue);
            $stmt->bindValue(':numero', $numero);
            $stmt->bindValue(':cp', $code_postal);
            $stmt->bindValue(':ville', $ville);
            $stmt->bindValue(':pays', $pays);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->_cnx->commit();
            return $data;
        } catch (PDOException $e) {
            $this->_cnx->rollBack();
            error_log($e->getMessage());
            return null;
        }
    }
    public function getClientByEmail($email) {
        try {
            $query = "SELECT c.*, a.rue, a.numero, a.code_postal, a.ville, a.pays 
                  FROM client c 
                  LEFT JOIN adresse a ON c.id_client = a.id_client 
                  WHERE c.email_client = :email";
            $res = $this->_cnx->prepare($query);
            $res->bindValue(':email', $email);
            $res->execute();
            return $res->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAllClients(): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_donnees_client");
            $stmt->execute();
            return array_map(fn($d) => $this->hydrate($d), $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getClientById(int $id): ?Client
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_donnees_client WHERE id_client = :id LIMIT 1");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $d = $stmt->fetch(PDO::FETCH_ASSOC);
            return $d ? $this->hydrate($d) : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // Retourne les statistiques d'achat d'un client : nb commandes, total dépensé, date dernière commande
    public function getStatsClient(int $id): array
    {
        $sql = "SELECT COUNT(*) AS nb_commandes,
                       COALESCE(SUM(total_commande::numeric), 0) AS total_depense,
                       MAX(date_commande) AS derniere_commande
                FROM commande WHERE id_client = :id";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)
                ?: ['nb_commandes' => 0, 'total_depense' => 0, 'derniere_commande' => null];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['nb_commandes' => 0, 'total_depense' => 0, 'derniere_commande' => null];
        }
    }


    // --- Écriture ---

    public function updateProfil(int $id, string $nom, string $prenom, string $email): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_profil_client(:id, :nom, :prenom, :email) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':nom', $nom);
            $stmt->bindValue(':prenom', $prenom);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateAdresse(int $id, string $rue, string $numero, string $cp, string $ville, string $pays): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_adresse_client(:id, :rue, :num, :cp, :ville, :pays) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':rue', $rue);
            $stmt->bindValue(':num', $numero);
            $stmt->bindValue(':cp', $cp);
            $stmt->bindValue(':ville', $ville);
            $stmt->bindValue(':pays', $pays);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updatePassword(int $id, string $password): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_password_client(:id, :mdp) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':mdp', $password);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function rechercher(string $terme): array {
        try {
            $sql = "SELECT * FROM v_donnees_client
                    WHERE unaccent(LOWER(nom_client)) LIKE unaccent(:q)
                       OR unaccent(LOWER(prenom_client)) LIKE unaccent(:q)
                       OR unaccent(LOWER(email_client)) LIKE unaccent(:q)
                    ORDER BY nom_client
                    LIMIT 4";
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':q', '%' . mb_strtolower($terme) . '%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // --- Statistiques dashboard ---

    public function countTotal(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM client")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    private function hydrate(array $d): Client
    {
        return new Client(
            id_client: (int)$d['id_client'],
            nom_client: $d['nom_client'],
            prenom_client: $d['prenom_client'],
            email_client: $d['email_client'],
            mot_de_passe: $d['mot_de_passe'] ?? '',
            rue: $d['rue'] ?? '',
            numero: $d['numero'] ?? '',
            code_postal: $d['code_postal'] ?? '',
            ville: $d['ville'] ?? '',
            pays: $d['pays'] ?? ''
        );
    }
}
