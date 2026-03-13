CREATE OR REPLACE FUNCTION ajout_categorie(p_nom TEXT) 
RETURNS INT AS $$
DECLARE v_id INT;
BEGIN
    SELECT id_categorie INTO v_id FROM categorie WHERE nom_categorie = p_nom;
    IF FOUND THEN
        RETURN -1;
    END IF;
    INSERT INTO categorie (nom_categorie) VALUES (p_nom);
    SELECT id_categorie INTO v_id FROM categorie WHERE nom_categorie = p_nom;
    RETURN v_id;
EXCEPTION WHEN OTHERS THEN
    RETURN 0;
END;
$$ LANGUAGE plpgsql;