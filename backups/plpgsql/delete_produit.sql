CREATE OR REPLACE FUNCTION delete_produit(p_id INT)
RETURNS BOOLEAN AS $$
BEGIN
    DELETE FROM produit WHERE id_produit = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
