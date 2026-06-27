CREATE OR REPLACE FUNCTION ajout_image_produit(p_id_produit INT, p_url TEXT)
RETURNS VOID AS $$
BEGIN
    INSERT INTO image_produit (id_image, id_produit, url_image, ordre)
    SELECT COALESCE(MAX(id_image), 0) + 1, p_id_produit, p_url, COALESCE(MAX(ordre), 0) + 1
    FROM image_produit;
END;
$$ LANGUAGE plpgsql;
