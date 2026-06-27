CREATE OR REPLACE FUNCTION get_ou_creer_panier(
    p_id_session TEXT,
    p_id_client  INT DEFAULT NULL
) RETURNS INT AS $$
DECLARE
    v_id_panier INT;
BEGIN
    SELECT id_panier INTO v_id_panier
    FROM panier WHERE id_session = p_id_session LIMIT 1;

    IF v_id_panier IS NOT NULL THEN
        IF p_id_client IS NOT NULL THEN
            UPDATE panier SET id_client = p_id_client
            WHERE id_panier = v_id_panier AND id_client IS NULL;
        END IF;
        RETURN v_id_panier;
    ELSE
        INSERT INTO panier (id_session, id_client)
        VALUES (p_id_session, p_id_client)
        RETURNING id_panier INTO v_id_panier;
        RETURN v_id_panier;
    END IF;
END;
$$ LANGUAGE plpgsql;
