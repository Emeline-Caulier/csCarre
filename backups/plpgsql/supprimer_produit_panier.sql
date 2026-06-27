CREATE OR REPLACE FUNCTION supprimer_produit_panier(p_id_panier INT, p_id_produit INT)
RETURNS BOOLEAN AS $$
BEGIN
    DELETE FROM panier_produit
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
