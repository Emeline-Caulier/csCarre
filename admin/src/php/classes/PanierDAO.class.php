<?php
// Accès aux tables panier et panier_produit. Mode hybride : id_session (anonyme) ou id_client (connecté).
// Toutes les écritures passent par des fonctions PL/pgSQL pour rester atomiques côté BDD.
class PanierDAO {
    private PDO $_cnx;

    public function __construct(PDO $cnx) {
        $this->_cnx = $cnx;
    }

    // Récupère ou crée le panier de la session. Si un id_client est fourni et que le panier
    // n'en a pas encore, on l'y associe (cas d'un client qui se connecte au cours de sa navigation).
    public function getPanier(string $id_session, ?int $id_client = null): ?int {
        try {
            $stmt = $this->_cnx->prepare("SELECT get_ou_creer_panier(:id_session, :id_client) AS retour");
            $stmt->bindValue(':id_session', $id_session, PDO::PARAM_STR);
            $stmt->bindValue(':id_client', $id_client, $id_client === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->execute();
            $retour = $stmt->fetchColumn();
            return $retour !== false && $retour !== null ? (int)$retour : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // Si le produit est déjà dans le panier, la quantité est incrémentée (géré côté PL/pgSQL).
    public function ajouterProduit(int $id_panier, int $id_produit, int $quantite = 1): bool {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT ajouter_produit_panier(:id_panier, :id_produit, :quantite) AS retour"
            );
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getContenu(int $id_panier): array {
        try {
            // Cast prix money -> numeric::text (cf. ProduitDAO)
            $sql = "SELECT pp.id_produit, pp.quantite, p.nom_produit, p.prix::numeric::text AS prix, p.stock,
                           (SELECT url_image FROM image_produit WHERE id_produit = p.id_produit ORDER BY ordre LIMIT 1) AS image_url
                    FROM panier_produit pp
                    JOIN produit p ON pp.id_produit = p.id_produit
                    WHERE pp.id_panier = :id_panier
                    ORDER BY p.nom_produit";

            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->execute();

            $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $contenu = [];

            foreach ($lignes as $ligne) {
                $contenu[] = new PanierDetail(
                    $ligne['id_produit'],
                    $ligne['quantite'],
                    $ligne['nom_produit'],
                    $ligne['prix'],
                    $ligne['image_url'],
                    (int)$ligne['stock']
                );
            }

            return $contenu;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateQuantite(int $id_panier, int $id_produit, int $quantite): bool {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT update_quantite_panier(:id_panier, :id_produit, :quantite) AS retour"
            );
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Renvoie le stock du produit et la quantité déjà présente dans le panier,
    // pour pouvoir afficher un message précis quand le stock devient insuffisant.
    public function getStockEtQtePanier(int $id_panier, int $id_produit): ?array {
        try {
            $sql = "SELECT p.stock,
                           COALESCE((SELECT pp.quantite FROM panier_produit pp
                                     WHERE pp.id_panier = :id_panier AND pp.id_produit = :id_produit), 0) AS qte_panier
                    FROM produit p WHERE p.id_produit = :id_produit";
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->execute();
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            return $r ? ['stock' => (int)$r['stock'], 'qte_panier' => (int)$r['qte_panier']] : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function supprimerProduit(int $id_panier, int $id_produit): bool {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT supprimer_produit_panier(:id_panier, :id_produit) AS retour"
            );
            $stmt->bindValue(':id_panier', $id_panier, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

}
