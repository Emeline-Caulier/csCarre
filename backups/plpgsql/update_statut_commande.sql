CREATE OR REPLACE FUNCTION update_statut_commande(p_id INT, p_statut TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    IF p_statut NOT IN ('en_attente', 'approuvée', 'envoyée', 'annulée') THEN
        RETURN FALSE;
    END IF;
    UPDATE commande SET statut_commande = p_statut WHERE id_commande = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
