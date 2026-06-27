CREATE OR REPLACE FUNCTION delete_promotion(p_id_promotion INT)
RETURNS BOOLEAN AS $$
BEGIN
    DELETE FROM promotion WHERE id_promotion = p_id_promotion;
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;
