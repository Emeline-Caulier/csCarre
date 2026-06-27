CREATE OR REPLACE FUNCTION update_password_client(p_id INT, p_mdp TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE client SET mot_de_passe = p_mdp WHERE id_client = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
