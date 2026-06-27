<?php

declare(strict_types=1);

// Accès aux données de la table avis via la vue v_avis_details
class AvisDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    public function getAllAvis(): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_avis_details ORDER BY date_avis DESC");
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    // Récupère les derniers avis approuvés pour les afficher sur la page d'accueil.
    public function getDerniersAvisApprouves(int $limit = 2): array {
        $sql = "SELECT * FROM v_avis_approuves LIMIT :limit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            // Retourne des objets pour être compatible avec votre code de vue existant
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erreur AvisDAO : " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_statut_avis(:id, :status) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // --- Statistiques dashboard ---

    public function countEnAttente(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM avis WHERE modere = 'en_attente'")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }


    public function getAvisByProduit(int $id_produit): array
    {
        $sql = "SELECT a.id_avis,
                       COALESCE(c.prenom_client, 'Anonyme') AS prenom_client,
                       COALESCE(c.nom_client, '') AS nom_client,
                       a.note_etoiles, a.commentaire, a.date_avis, a.photo, a.modere
                FROM avis a
                LEFT JOIN client c ON a.id_client = c.id_client
                WHERE a.id_produit = :id AND a.modere = 'approuvé'
                ORDER BY a.date_avis DESC";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id_produit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Retourne les couples (commande, produit) qu'un client peut évaluer :
    // commande envoyée, et avis pas encore donné pour cette commande spécifique
    // (un même produit peut donc apparaître plusieurs fois s'il a été acheté
    // dans plusieurs commandes distinctes).
    public function getProduitsAEvaluer(int $idClient): array
    {
        $sql = "SELECT cmd.id_commande,
                       cmd.date_commande,
                       p.id_produit,
                       p.nom_produit,
                       (SELECT url_image
                          FROM image_produit
                         WHERE id_produit = p.id_produit
                         ORDER BY ordre, id_image
                         LIMIT 1) AS url_image
                FROM commande_produit cp
                JOIN commande cmd ON cp.id_commande = cmd.id_commande
                JOIN produit p ON cp.id_produit = p.id_produit
                WHERE cmd.id_client = :id_client
                  AND cmd.statut_commande = 'envoyée'
                  AND NOT EXISTS (
                      SELECT 1 FROM avis a
                       WHERE a.id_commande = cmd.id_commande
                         AND a.id_produit = cp.id_produit
                  )
                ORDER BY cmd.date_commande DESC, p.nom_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Vérifie qu'un client a bien le droit d'évaluer un produit pour une
    // commande donnée : la commande lui appartient, est expédiée, contient
    // bien ce produit, et aucun avis n'existe déjà pour ce couple.
    public function peutEvaluer(int $idClient, int $idCommande, int $idProduit): bool
    {
        $sql = "SELECT EXISTS (
                    SELECT 1
                      FROM commande_produit cp
                      JOIN commande cmd ON cp.id_commande = cmd.id_commande
                     WHERE cmd.id_client = :id_client
                       AND cmd.id_commande = :id_commande
                       AND cp.id_produit = :id_produit
                       AND cmd.statut_commande = 'envoyée'
                ) AND NOT EXISTS (
                    SELECT 1 FROM avis
                     WHERE id_commande = :id_commande
                       AND id_produit = :id_produit
                )";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->bindValue(':id_commande', $idCommande, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Insère un nouvel avis lié à une commande précise.
    // Le champ modere passe à 'en_attente' par défaut côté BDD (validation manuelle ensuite).
    public function addAvis(int $idClient, int $idCommande, int $idProduit, int $note, string $commentaire, ?string $photo): bool
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT ajout_avis(:id_client, :id_commande, :id_produit, :note, :commentaire, :photo) AS retour"
            );
            $stmt->bindValue(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->bindValue(':id_commande', $idCommande, PDO::PARAM_INT);
            $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
            $stmt->bindValue(':note', $note, PDO::PARAM_INT);
            $stmt->bindValue(':commentaire', $commentaire);
            $stmt->bindValue(':photo', $photo);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getMoyenneByProduit(int $id_produit): float
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT COALESCE(AVG(note_etoiles), 0) FROM avis WHERE id_produit = :id AND modere = 'approuvé'"
            );
            $stmt->bindValue(':id', $id_produit, PDO::PARAM_INT);
            $stmt->execute();
            return (float)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0.0;
        }
    }

    private function hydrate(array $d): Avis
    {
        return new Avis(
            id_avis: (int)$d['id_avis'],
            prenom_client: $d['prenom_client'],
            nom_client: $d['nom_client'],
            nom_produit: $d['nom_produit'],
            note_etoiles: (int)$d['note_etoiles'],
            commentaire: $d['commentaire'],
            date_avis: $d['date_avis'],
            photo: $d['photo'] ?? null,
            modere: $d['modere'],
        );
    }
}
