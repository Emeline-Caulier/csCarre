CREATE OR REPLACE FUNCTION update_categorie(p_id INT, p_nom TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE categorie SET nom_categorie = p_nom WHERE id_categorie = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
