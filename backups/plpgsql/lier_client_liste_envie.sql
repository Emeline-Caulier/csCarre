CREATE OR REPLACE FUNCTION lier_client_liste_envie(p_id_session TEXT, p_id_client INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM liste_envie
    WHERE id_session = p_id_session
      AND id_client IS NULL
      AND id_produit IN (
          SELECT id_produit FROM liste_envie WHERE id_client = p_id_client
      );

    UPDATE liste_envie
    SET id_client = p_id_client
    WHERE id_session = p_id_session AND id_client IS NULL;
END;
$$ LANGUAGE plpgsql;
