CREATE OR REPLACE FUNCTION ajouter_produit_panier(
    p_id_panier  INT,
    p_id_produit INT,
    p_quantite   INT DEFAULT 1
) RETURNS BOOLEAN AS $$
DECLARE
    v_stock         INT;
    v_qte_actuelle  INT;
BEGIN
    -- Stock disponible du produit
    SELECT stock INTO v_stock FROM produit WHERE id_produit = p_id_produit;
    IF v_stock IS NULL THEN
        RETURN FALSE;
    END IF;

    -- Quantité déjà présente dans le panier (0 si absent)
    SELECT COALESCE(quantite, 0) INTO v_qte_actuelle
    FROM panier_produit
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    v_qte_actuelle := COALESCE(v_qte_actuelle, 0);

    -- Refus si la quantité demandée dépasse le stock disponible
    IF v_qte_actuelle + p_quantite > v_stock THEN
        RETURN FALSE;
    END IF;

    IF v_qte_actuelle > 0 THEN
        UPDATE panier_produit
        SET quantite = quantite + p_quantite
        WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    ELSE
        INSERT INTO panier_produit (id_panier, id_produit, quantite)
        VALUES (p_id_panier, p_id_produit, p_quantite);
    END IF;
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$ LANGUAGE plpgsql;
