CREATE OR REPLACE FUNCTION update_statut_message(p_id INT, p_statut TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE message_contact SET statut = p_statut WHERE id_message = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
