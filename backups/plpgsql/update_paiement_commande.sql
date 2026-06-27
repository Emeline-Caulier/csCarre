CREATE OR REPLACE FUNCTION update_paiement_commande(p_id INT, p_statut BOOLEAN)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE commande SET statut_paiement = p_statut WHERE id_commande = p_id;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
