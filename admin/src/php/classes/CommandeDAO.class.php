<?php

// Accès aux données de la table commande et de la vue v_commandes_client
class CommandeDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // --- Lecture ---

    public function getAllCommandes(): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_commandes_client");
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getCommandesByClient(int $id_client): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_commandes_client WHERE id_client = :id");
            $stmt->bindValue(':id', $id_client, PDO::PARAM_INT);
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Retourne les informations d'une commande avec client et adresse de livraison
    public function getCommandeDetails(int $id): ?array
    {
        // Cast total_commande money -> numeric::text (sinon préfixe '$' en locale en_US)
        $sql = "SELECT cmd.id_commande, cmd.date_commande, cmd.total_commande::numeric::text AS total_commande,
                       cmd.statut_paiement, cmd.statut_commande,
                       cl.nom_client, cl.prenom_client, cl.email_client,
                       a.rue, a.numero, a.code_postal, a.ville, a.pays
                FROM commande cmd
                JOIN client cl ON cmd.id_client = cl.id_client
                JOIN adresse a ON cmd.id_adresse = a.id_adresse
                WHERE cmd.id_commande = :id";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $d = $stmt->fetch(PDO::FETCH_ASSOC);
            return $d ?: null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // Retourne les lignes de produits d'une commande (quantité, prix au moment de l'achat)
    public function getLignesCommande(int $id): array
    {
        // Cast prix_unitaire_achat money -> numeric::text (sinon préfixe '$' en locale en_US)
        $sql = "SELECT cp.id_produit, p.nom_produit, cp.quantite_commandee, cp.prix_unitaire_achat::numeric::text AS prix_unitaire_achat
                FROM commande_produit cp
                JOIN produit p ON cp.id_produit = p.id_produit
                WHERE cp.id_commande = :id
                ORDER BY p.nom_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function rechercher(string $terme): array {
        try {
            $sql = "SELECT * FROM v_commandes_client
                    WHERE unaccent(LOWER(nom_client)) LIKE unaccent(:q)
                       OR unaccent(LOWER(prenom_client)) LIKE unaccent(:q)
                       OR id_commande::text LIKE :q
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

    public function countEnAttente(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM commande WHERE statut_commande = 'en_attente'")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    public function countTotal(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM commande")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    public function getCATotal(): string
    {
        try {
            return (string)$this->_cnx->query(
                "SELECT COALESCE(SUM(total_commande::numeric), 0) FROM commande WHERE statut_paiement = true"
            )->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return '0'; }
    }

    public function getCaMois(): string
    {
        try {
            return (string)$this->_cnx->query(
                "SELECT COALESCE(SUM(total_commande::numeric), 0) FROM commande
                 WHERE statut_paiement = true
                   AND date_trunc('month', date_commande) = date_trunc('month', NOW())"
            )->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return '0'; }
    }

    public function getDernieres(int $limit = 5): array
    {
        // Cast total_commande money -> numeric::text (sinon préfixe '$' en locale en_US, qui rend (float) = 0)
        $sql = "SELECT cmd.id_commande, cmd.date_commande, cmd.total_commande::numeric::text AS total_commande, cmd.statut_paiement, cmd.statut_commande,
                cl.nom_client, cl.prenom_client FROM commande cmd
                JOIN client cl ON cmd.id_client = cl.id_client
                ORDER BY cmd.date_commande DESC, cmd.id_commande DESC
                LIMIT :limit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { error_log($e->getMessage()); return []; }
    }

    // --- Écriture ---

    public function updatePaiement(int $id, bool $statut): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_paiement_commande(:id, :statut) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':statut', $statut, PDO::PARAM_BOOL);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateStatut(int $id, string $statut): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_statut_commande(:id, :statut) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':statut', $statut);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function creerCommande(int $id_client, int $id_panier, float $total): ?int
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT creer_commande(:id_client, :id_panier, :total) AS retour");
            $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->bindValue(':total', $total);
            $stmt->execute();
            $retour = $stmt->fetchColumn();
            return $retour !== false && $retour !== null ? (int)$retour : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // --- Privé ---

    private function hydrate(array $d): Commande
    {
        return new Commande(
            id_commande: (int)$d['id_commande'],
            date_commande: $d['date_commande'],
            id_client: (int)$d['id_client'],
            nom_client: $d['nom_client'],
            prenom_client: $d['prenom_client'],
            email_client: $d['email_client'],
            total_commande: $d['total_commande'],
            statut_paiement: (bool)$d['statut_paiement'],
            statut_commande: $d['statut_commande'],
            ville: $d['ville'],
            pays: $d['pays']
        );
    }
}
