CREATE OR REPLACE FUNCTION public.ajout_client(
	p_email text,
	p_password text,
	p_nom text,
	p_prenom text,
	p_rue text,
	p_numero text,
	p_cp text,
	p_ville text,
	p_pays text)
    RETURNS integer
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
DECLARE 
    v_id_client int;
BEGIN
    INSERT INTO public.client (nom_client, prenom_client, email_client, mot_de_passe)
    VALUES (p_nom, p_prenom, p_email, p_password)
    ON CONFLICT (email_client) DO NOTHING 
    RETURNING id_client INTO v_id_client;

    IF v_id_client IS NOT NULL THEN
        INSERT INTO public.adresse (id_client, rue, numero, code_postal, ville, pays)
        VALUES (v_id_client, p_rue, p_numero, p_cp, p_ville, p_pays);
        
        RETURN v_id_client; 
    END IF;

    SELECT id_client INTO v_id_client FROM public.client WHERE email_client = p_email;
    IF FOUND THEN
        RETURN -1;
    END IF;

    RETURN 0;
END;
$BODY$;

ALTER FUNCTION public.ajout_client(text, text, text, text, text, text, text, text, text)
    OWNER TO anonyme;

