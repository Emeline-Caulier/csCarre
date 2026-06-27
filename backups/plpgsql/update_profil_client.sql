CREATE OR REPLACE FUNCTION update_profil_client(
    p_id     INT,
    p_nom    TEXT,
    p_prenom TEXT,
    p_email  TEXT
) RETURNS BOOLEAN AS $$
BEGIN
    UPDATE client
    SET nom_client    = p_nom,
        prenom_client = p_prenom,
        email_client  = p_email
    WHERE id_client = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
