CREATE OR REPLACE FUNCTION public.ajout_categorie(
	p_nom text)
    RETURNS integer
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
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
$BODY$;

ALTER FUNCTION public.ajout_categorie(text)
    OWNER TO anonyme;

