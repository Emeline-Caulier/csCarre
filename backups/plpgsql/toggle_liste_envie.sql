CREATE OR REPLACE FUNCTION toggle_liste_envie(
    p_id_session TEXT,
    p_id_produit INT,
    p_id_client  INT DEFAULT NULL
) RETURNS BOOLEAN AS $$
DECLARE
    v_id_liste INT;
BEGIN
    IF p_id_client IS NOT NULL THEN
        SELECT id_liste_envie INTO v_id_liste
        FROM liste_envie
        WHERE id_client = p_id_client AND id_produit = p_id_produit
        LIMIT 1;
    ELSE
        SELECT id_liste_envie INTO v_id_liste
        FROM liste_envie
        WHERE id_session = p_id_session AND id_client IS NULL AND id_produit = p_id_produit
        LIMIT 1;
    END IF;

    IF v_id_liste IS NOT NULL THEN
        DELETE FROM liste_envie WHERE id_liste_envie = v_id_liste;
        RETURN FALSE;
    ELSE
        INSERT INTO liste_envie (id_session, id_client, id_produit, date_ajout)
        VALUES (p_id_session, p_id_client, p_id_produit, CURRENT_DATE);
        RETURN TRUE;
    END IF;
END;
$$ LANGUAGE plpgsql;
