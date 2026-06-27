CREATE OR REPLACE FUNCTION update_champ_promotion(
    p_id_promotion INT,
    p_champ        TEXT,
    p_valeur       TEXT
) RETURNS BOOLEAN AS $$
BEGIN
    IF p_champ = 'taux_reduction' THEN
        UPDATE promotion SET taux_reduction = p_valeur::NUMERIC WHERE id_promotion = p_id_promotion;
    ELSIF p_champ = 'date_debut' THEN
        UPDATE promotion SET date_debut = p_valeur::DATE WHERE id_promotion = p_id_promotion;
    ELSIF p_champ = 'date_fin' THEN
        UPDATE promotion SET date_fin = p_valeur::DATE WHERE id_promotion = p_id_promotion;
    ELSE
        RETURN FALSE;
    END IF;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
