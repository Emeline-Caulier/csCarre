CREATE OR REPLACE FUNCTION set_est_nouveau(p_id INT, p_val BOOLEAN)
RETURNS VOID AS $$
BEGIN
    UPDATE produit SET est_nouveau = p_val WHERE id_produit = p_id;
END;
$$ LANGUAGE plpgsql;
