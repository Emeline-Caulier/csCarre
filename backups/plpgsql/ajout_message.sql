CREATE OR REPLACE FUNCTION ajout_message(
    p_id_client    INT,
    p_nom          TEXT,
    p_email        TEXT,
    p_num_commande INT,
    p_sujet        TEXT,
    p_contenu      TEXT
) RETURNS BOOLEAN AS $$
BEGIN
    INSERT INTO message_contact (id_client, nom_contact, email_contact, num_commande, sujet, contenu, date_message, statut)
    VALUES (p_id_client, p_nom, p_email, p_num_commande, p_sujet, p_contenu, NOW(), 'non_lu');
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$ LANGUAGE plpgsql;
