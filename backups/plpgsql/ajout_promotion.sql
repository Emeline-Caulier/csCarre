CREATE OR REPLACE FUNCTION ajout_promotion(
    p_id_produit INT,
    p_taux       NUMERIC,
    p_date_debut DATE,
    p_date_fin   DATE
) RETURNS BOOLEAN AS $$
BEGIN
    INSERT INTO promotion (id_produit, taux_reduction, date_debut, date_fin)
    VALUES (p_id_produit, p_taux, p_date_debut, p_date_fin);
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$ LANGUAGE plpgsql;
