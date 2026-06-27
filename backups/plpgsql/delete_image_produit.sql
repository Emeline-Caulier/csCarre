CREATE OR REPLACE FUNCTION delete_image_produit(p_id_image INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM image_produit WHERE id_image = p_id_image;
END;
$$ LANGUAGE plpgsql;
