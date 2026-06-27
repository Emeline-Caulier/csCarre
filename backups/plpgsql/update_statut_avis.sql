CREATE OR REPLACE FUNCTION update_statut_avis(p_id INT, p_statut TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE avis SET modere = p_statut WHERE id_avis = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
