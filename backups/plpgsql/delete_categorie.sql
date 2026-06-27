CREATE OR REPLACE FUNCTION delete_categorie(p_id INT)
RETURNS BOOLEAN AS $$
BEGIN
    DELETE FROM categorie WHERE id_categorie = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
