<?php

// Accès aux données des tables produit et image_produit.
class ProduitDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // --- Lecture ---

    public function getAllProduits(): array
    {
        $sql = $this->sqlBase() . " WHERE p.en_vitrine = true ORDER BY p.id_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getProduitsByCategorie(int $id_cat): array
    {
        $sql = $this->sqlBase() . " WHERE p.id_categorie = :id_cat AND p.en_vitrine = true ORDER BY p.id_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_cat', $id_cat, PDO::PARAM_INT);
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getProduitById(int $id): ?Produit
    {
        $sql = $this->sqlBase() . " WHERE p.id_produit = :id";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $d = $stmt->fetch(PDO::FETCH_ASSOC);
            return $d ? $this->hydrate($d) : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAllForDropdown(): array
    {
        try {
            // Cast money -> numeric::text pour éviter le préfixe '$' lié à la locale Postgres
            return $this->_cnx->query("SELECT id_produit, nom_produit, prix::numeric::text AS prix FROM produit WHERE en_vitrine = true ORDER BY nom_produit")->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // --- Écriture et mises à jour ---

    public function addProduit(int $id_categorie, string $nom, string $description, string $prix, int $stock): ?int
    {
        $query = "SELECT ajout_produit(:nom, :stock, :prix::numeric, :desc, :id_cat) AS retour";
        try {
            $this->_cnx->beginTransaction();
            $stmt = $this->_cnx->prepare($query);
            $stmt->bindValue(':nom', $nom);
            $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindValue(':prix', $prix);
            $stmt->bindValue(':desc', $description);
            $stmt->bindValue(':id_cat', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
            $retour = $stmt->fetchColumn();
            $this->_cnx->commit();
            return $retour ? (int)$retour : null;
        } catch (PDOException $e) {
            if ($this->_cnx->inTransaction()) $this->_cnx->rollBack();
            error_log($e->getMessage());
            return null;
        }
    }

    public function updateProduit(int $id, int $id_categorie, string $nom, string $description, string $prix, int $stock, bool $estNouveau = false): int
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_produit(:id, :id_cat, :nom, :desc, :prix::numeric, :stock, :est_nouveau) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':id_cat', $id_categorie, PDO::PARAM_INT);
            $stmt->bindValue(':nom', $nom);
            $stmt->bindValue(':desc', $description);
            $stmt->bindValue(':prix', $prix);
            $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindValue(':est_nouveau', $estNouveau, PDO::PARAM_BOOL);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function modifierChamp(int $id, string $champ, $valeur): bool
    {
        try {
            // Cas spécial : la colonne prix est de type "money", le parsing dépend de la locale
            // serveur (fr_FR : "." = séparateur de milliers). On force un cast via ::numeric
            // pour interpréter "12.50" comme un nombre décimal sans ambiguïté.
            if ($champ === 'prix') {
                $stmt = $this->_cnx->prepare("UPDATE produit SET prix = :val::numeric::money WHERE id_produit = :id");
                $stmt->bindValue(':val', (string)$valeur);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            }
            $stmt = $this->_cnx->prepare("SELECT update_champ_produit(:champ, :val, :id)");
            $stmt->bindValue(':champ', $champ);
            $stmt->bindValue(':val', (string)$valeur);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function modifierStatutNouveau(int $id, bool $val): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT set_est_nouveau(:id, :val)");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':val', $val, PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function retirerDeLaVitrine(int $id): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT retirer_de_la_vitrine(:id) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteProduit(int $id): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT delete_produit(:id) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // --- Images ---

    public function getImagesByProduit(int $id_produit): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT id_image, url_image, ordre FROM image_produit WHERE id_produit = :id ORDER BY ordre");
            $stmt->bindValue(':id', $id_produit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addImage(int $id_produit, string $url): void
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT ajout_image_produit(:id_produit, :url)");
            $stmt->bindValue(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->bindValue(':url', $url);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function modifierOrdreImage(int $idImage, int $ordre): bool
    {
        try {
            $stmt = $this->_cnx->prepare("UPDATE image_produit SET ordre = :ordre WHERE id_image = :id");
            $stmt->bindValue(':ordre', $ordre, PDO::PARAM_INT);
            $stmt->bindValue(':id', $idImage, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteImage(int $id_image): void
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT delete_image_produit(:id)");
            $stmt->bindValue(':id', $id_image, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    // --- Recherches et AJAX ---

    public function rechercherProduits(string $q): array
    {
        $sql = $this->sqlBase() . " WHERE p.en_vitrine = true AND unaccent(LOWER(p.nom_produit)) LIKE unaccent(LOWER(:q)) ORDER BY p.nom_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':q', '%' . $q . '%');
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function rechercherPourNouveaute(string $terme): array
    {
        $sql = $this->sqlBase() . " WHERE p.en_vitrine = true AND p.est_nouveau = false AND unaccent(LOWER(p.nom_produit)) LIKE unaccent(:q) ORDER BY p.nom_produit LIMIT 8";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':q', '%' . mb_strtolower($terme) . '%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getInfosPourNouveaute(int $id): ?array
    {
        $sql = $this->sqlBase() . " WHERE p.id_produit = :id";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res) return null;
            return [
                'id_produit' => $res['id_produit'],
                'nom_produit' => $res['nom_produit'],
                'prix' => $res['prix'],
                'image_url' => $res['photo_principale']
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getNouvelleCollection(): array
    {
        $sql = $this->sqlBase() . " WHERE p.est_nouveau = true AND p.en_vitrine = true ORDER BY p.nom_produit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getSimilairesByMotsCles(int $id_exclu, array $mots, int $limit = 4): array
    {
        if (empty($mots)) return [];
        $conditions = [];
        $params = [];
        foreach ($mots as $i => $mot) {
            $conditions[] = "LOWER(p.nom_produit) LIKE :mot$i";
            $params[":mot$i"] = '%' . mb_strtolower($mot) . '%';
        }
        $sql = $this->sqlBase() . " WHERE p.en_vitrine = true AND p.id_produit != :id_exclu AND (" . implode(' OR ', $conditions) . ") LIMIT :limit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':id_exclu', $id_exclu, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // --- Statistiques (dashboard) ---

    public function countTotal(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM produit WHERE en_vitrine = true")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    public function countRuptures(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM produit WHERE en_vitrine = true AND stock = 0")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    public function countASurveiller(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM produit WHERE en_vitrine = true AND stock > 0 AND stock <= 5")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    public function getTopVentes(int $limit = 3): array
    {
        $sql = "SELECT p.id_produit, p.id_categorie, p.nom_produit, SUM(cp.quantite_commandee) AS total_vendu,
                       (SELECT url_image FROM image_produit ip WHERE ip.id_produit = p.id_produit ORDER BY ip.ordre LIMIT 1) AS image_url
                FROM commande_produit cp
                JOIN produit p ON cp.id_produit = p.id_produit
                WHERE p.en_vitrine = true
                GROUP BY p.id_produit, p.id_categorie, p.nom_produit
                ORDER BY total_vendu DESC LIMIT :limit";
        try {
            $stmt = $this->_cnx->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { error_log($e->getMessage()); return []; }
    }

    public function getCountByCategorie(): array
    {
        try {
            $rows = $this->_cnx->query("SELECT id_categorie, COUNT(*) AS nb FROM produit WHERE en_vitrine = true GROUP BY id_categorie")->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            foreach ($rows as $r) { $result[(int)$r['id_categorie']] = (int)$r['nb']; }
            return $result;
        } catch (PDOException $e) { error_log($e->getMessage()); return []; }
    }

    public function getStockStatsByCategorie(): array
    {
        $sql = "SELECT id_categorie, COUNT(*) FILTER (WHERE stock = 0) AS rupture, COUNT(*) FILTER (WHERE stock > 0 AND stock <= 5) AS surveiller, COUNT(*) FILTER (WHERE stock > 5) AS en_stock FROM produit WHERE en_vitrine = true GROUP BY id_categorie";
        try {
            $rows = $this->_cnx->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            foreach ($rows as $r) {
                $result[(int)$r['id_categorie']] = [
                    'rupture' => (int)$r['rupture'],
                    'surveiller' => (int)$r['surveiller'],
                    'en_stock' => (int)$r['en_stock'],
                ];
            }
            return $result;
        } catch (PDOException $e) { error_log($e->getMessage()); return []; }
    }

    // --- Privé ---

    private function sqlBase(): string
    {
        return "SELECT p.id_produit, p.id_categorie, c.nom_categorie, p.nom_produit, p.description, p.prix::numeric::text AS prix, p.stock, p.est_nouveau, (SELECT url_image FROM image_produit WHERE id_produit = p.id_produit ORDER BY ordre LIMIT 1) AS photo_principale FROM produit p JOIN categorie c ON p.id_categorie = c.id_categorie";
    }

    private function hydrate(array $d): Produit
    {
        return new ProduitCategorie(
            id_produit: (int)$d['id_produit'],
            id_categorie: (int)$d['id_categorie'],
            nom_produit: $d['nom_produit'],
            description: $d['description'],
            prix: $d['prix'],
            stock: (int)$d['stock'],
            nom_categorie: $d['nom_categorie'],
            photo_principale: $d['photo_principale'] ?? null,
            est_nouveau: (bool)($d['est_nouveau'] ?? false)
        );
    }
}