CREATE OR REPLACE FUNCTION update_produit(
    p_id           INT,
    p_id_categorie INT,
    p_nom          TEXT,
    p_description  TEXT,
    p_prix         NUMERIC,
    p_stock        INT,
    p_est_nouveau  BOOLEAN
) RETURNS BOOLEAN AS $$
BEGIN
    UPDATE produit
    SET id_categorie = p_id_categorie,
        nom_produit  = p_nom,
        description  = p_description,
        prix         = p_prix,
        stock        = p_stock,
        est_nouveau  = p_est_nouveau
    WHERE id_produit = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
