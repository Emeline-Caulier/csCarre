CREATE OR REPLACE FUNCTION update_quantite_panier(
    p_id_panier  INT,
    p_id_produit INT,
    p_quantite   INT
) RETURNS BOOLEAN AS $$
DECLARE
    v_stock INT;
BEGIN
    -- Refus si la quantité demandée dépasse le stock disponible
    SELECT stock INTO v_stock FROM produit WHERE id_produit = p_id_produit;
    IF v_stock IS NULL OR p_quantite > v_stock THEN
        RETURN FALSE;
    END IF;

    UPDATE panier_produit
    SET quantite = p_quantite
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
