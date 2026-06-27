CREATE OR REPLACE FUNCTION ajout_avis(
    p_id_client   INT,
    p_id_commande INT,
    p_id_produit  INT,
    p_note        INT,
    p_commentaire TEXT,
    p_photo       TEXT DEFAULT NULL
) RETURNS BOOLEAN AS $$
BEGIN
    INSERT INTO avis (id_client, id_commande, id_produit, note_etoiles, commentaire, date_avis, photo)
    VALUES (p_id_client, p_id_commande, p_id_produit, p_note, p_commentaire, CURRENT_DATE, p_photo);
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$ LANGUAGE plpgsql;
