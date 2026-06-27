CREATE OR REPLACE FUNCTION retirer_de_la_vitrine(p_id_produit INT)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE produit SET en_vitrine = false WHERE id_produit = p_id_produit;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
