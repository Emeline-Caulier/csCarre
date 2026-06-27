CREATE OR REPLACE FUNCTION update_adresse_client(
    p_id    INT,
    p_rue   TEXT,
    p_num   TEXT,
    p_cp    TEXT,
    p_ville TEXT,
    p_pays  TEXT
) RETURNS BOOLEAN AS $$
BEGIN
    UPDATE adresse
    SET rue         = p_rue,
        numero      = p_num,
        code_postal = p_cp,
        ville       = p_ville,
        pays        = p_pays
    WHERE id_client = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
