<?php
declare(strict_types=1);

class PromotionDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }
    // Récupère les promotions actives pour la page d'accueil.
    // S'appuie sur la vue v_produits_promotion et joint l'image principale.
    public function getPromotionsAccueil(int $limit = 4): array
    {
        $sql = "SELECT p.*,
                (SELECT url_image FROM image_produit WHERE id_produit = p.id_produit ORDER BY ordre LIMIT 1) as image_url
                FROM v_produits_promotion p
                JOIN produit pr ON p.id_produit = pr.id_produit
                WHERE pr.en_vitrine = true
                LIMIT :limit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erreur PromotionDAO : " . $e->getMessage());
            return [];
        }
    }
    // Récupère les données de promotion calculées (prix_reduit, pourcentage) depuis la vue SQL.
    // Retourne null si aucune promotion n'est active pour ce produit à la date du jour.
    public function getPromoActive(int $idProduit): ?object
    {
        try {
            $sql = "SELECT prix_reduit, reduction_pourcent 
                FROM v_produits_promotion 
                WHERE id_produit = :id";
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $idProduit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur PromotionDAO::getPromoActive : " . $e->getMessage());
            return null;
        }
    }
    // Récupère toutes les promotions actives avec les infos du produit et l'image
    public function getAllWithDetails(): array
    {
        try {
            // Cast prix money -> numeric::text (sinon préfixe '$' en locale en_US)
            $sql = "
                SELECT p.id_promotion, p.taux_reduction, p.date_debut, p.date_fin,
                       pr.id_produit, pr.nom_produit, pr.prix::numeric::text AS prix,
                       (SELECT url_image FROM image_produit ip WHERE ip.id_produit = pr.id_produit ORDER BY ip.ordre LIMIT 1) as image_url
                FROM promotion p
                JOIN produit pr ON p.id_produit = pr.id_produit
                WHERE pr.en_vitrine = true
                ORDER BY p.date_fin ASC
            ";
            return $this->_cnx->query($sql)->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Vérifie si un produit est déjà dans la table promotion
    public function existeDeja(int $idProduit): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT COUNT(*) FROM promotion WHERE id_produit = ?");
            $stmt->execute([$idProduit]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function insert(int $idProduit, float $taux, string $dateDebut, string $dateFin): bool
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT ajout_promotion(:id_produit, :taux, :date_debut::date, :date_fin::date) AS retour"
            );
            $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
            $stmt->bindValue(':taux', $taux);
            $stmt->bindValue(':date_debut', $dateDebut);
            $stmt->bindValue(':date_fin', $dateFin);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $idPromotion): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT delete_promotion(:id) AS retour");
            $stmt->bindValue(':id', $idPromotion, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Retourne toutes les promotions indexées par id_produit (pour affichage dans l'inventaire)
    public function getAllIndexedByProduit(): array
    {
        try {
            $sql = "SELECT id_produit, id_promotion, prix_reduit, reduction_pourcent FROM v_produits_promotion";
            $rows = $this->_cnx->query($sql)->fetchAll(PDO::FETCH_OBJ);

            $index = [];
            foreach ($rows as $row) {
                $index[$row->id_produit] = $row;
            }
            return $index;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateChamp(int $idPromotion, string $champ, string $valeur): bool
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT update_champ_promotion(:id, :champ, :valeur) AS retour"
            );
            $stmt->bindValue(':id', $idPromotion, PDO::PARAM_INT);
            $stmt->bindValue(':champ', $champ);
            $stmt->bindValue(':valeur', $valeur);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Récupère une promotion avec les détails produit, par id_produit (utile après insert)
    public function getDetailsByProduitId(int $idProduit): ?object
    {
        try {
            // Cast prix money -> numeric::text (sinon préfixe '$' en locale en_US)
            $sql = "
                SELECT p.id_promotion, p.taux_reduction, p.date_debut, p.date_fin,
                       pr.id_produit, pr.nom_produit, pr.prix::numeric::text AS prix,
                       (SELECT url_image FROM image_produit ip WHERE ip.id_produit = pr.id_produit ORDER BY ip.ordre LIMIT 1) as image_url
                FROM promotion p
                JOIN produit pr ON p.id_produit = pr.id_produit
                WHERE p.id_produit = :id_produit
                ORDER BY p.id_promotion DESC
                LIMIT 1
            ";
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }






}