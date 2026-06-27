<?php
declare(strict_types=1);

// Accès à la table liste_envie. Mode hybride : id_session (anonyme) ou id_client (connecté).
class ListeEnvieDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // Ajoute si absent, retire si présent. Renvoie true (ajout), false (retrait) ou null (erreur).
    // On utilise fetch(FETCH_NUM) car le driver pgsql traduit BOOLEAN en bool PHP :
    // fetchColumn() rendrait [false] indistinguable de "aucune ligne retournée".
    public function toggle(string $id_session, int $id_produit, ?int $id_client): ?bool
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT toggle_liste_envie(:id_session, :id_produit, :id_client) AS retour"
            );
            $stmt->bindValue(':id_session', $id_session, PDO::PARAM_STR);
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->bindValue(':id_client', $id_client, $id_client === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_NUM);
            if ($row === false) return null;
            return (bool)$row[0];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getIdsFavoris(string $id_session, ?int $id_client): array
    {
        try {
            $stmt = $this->_cnx->prepare($this->sqlWhere("SELECT id_produit FROM liste_envie", $id_client));
            $this->bindIdentite($stmt, $id_session, $id_client);
            $stmt->execute();
            return array_map('intval', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_produit'));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getFavorisAvecProduits(string $id_session, ?int $id_client): array
    {
        // Cast prix money -> numeric::text (cf. ProduitDAO)
        $base = "SELECT le.id_liste_envie, le.date_ajout, p.id_produit, p.nom_produit,
                        p.prix::numeric::text AS prix, p.stock,
                        (SELECT url_image FROM image_produit WHERE id_produit = p.id_produit ORDER BY ordre LIMIT 1) AS image_url,
                        vp.prix_reduit, vp.reduction_pourcent
                 FROM liste_envie le
                 JOIN produit p ON le.id_produit = p.id_produit
                 LEFT JOIN v_produits_promotion vp ON vp.id_produit = p.id_produit";
        try {
            $stmt = $this->_cnx->prepare($this->sqlWhere($base, $id_client) . " ORDER BY le.date_ajout DESC, le.id_liste_envie DESC");
            $this->bindIdentite($stmt, $id_session, $id_client);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function count(string $id_session, ?int $id_client): int
    {
        try {
            $stmt = $this->_cnx->prepare($this->sqlWhere("SELECT COUNT(*) FROM liste_envie", $id_client));
            $this->bindIdentite($stmt, $id_session, $id_client);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    // À la connexion : on rattache les favoris de la session au compte. En cas de conflit
    // (produit déjà en favori sur le compte), la ligne de session est supprimée pour éviter
    // les doublons. La déduplication est gérée côté PL/pgSQL par lier_client_liste_envie().
    public function lierClient(string $id_session, int $id_client): void
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT lier_client_liste_envie(:id_session, :id_client)");
            $stmt->bindValue(':id_session', $id_session, PDO::PARAM_STR);
            $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private function trouver(string $id_session, int $id_produit, ?int $id_client): mixed
    {
        $sql = $this->sqlWhere("SELECT id_liste_envie FROM liste_envie", $id_client)
             . " AND id_produit = :id_produit LIMIT 1";
        $stmt = $this->_cnx->prepare($sql);
        $this->bindIdentite($stmt, $id_session, $id_client);
        $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    // Client connecté : filtre par id_client. Visiteur anonyme : id_session + id_client IS NULL.
    private function sqlWhere(string $base, ?int $id_client): string
    {
        return $id_client !== null
            ? "$base WHERE id_client = :id_client"
            : "$base WHERE id_session = :id_session AND id_client IS NULL";
    }

    private function bindIdentite(PDOStatement $stmt, string $id_session, ?int $id_client): void
    {
        if ($id_client !== null) {
            $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':id_session', $id_session, PDO::PARAM_STR);
        }
    }
}
