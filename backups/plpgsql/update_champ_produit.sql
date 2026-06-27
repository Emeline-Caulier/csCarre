CREATE OR REPLACE FUNCTION update_champ_produit(p_champ text, p_valeur text, p_id int) RETURNS integer AS '
BEGIN
    EXECUTE format(''UPDATE produit SET %I = %L WHERE id_produit = %L'', p_champ, p_valeur, p_id);
    -- execute format : utilisé lorsque les champs sont dynamiques
    -- %I : remplace le nom de colonne, de manière sécurisée (échappement pour éviter les injections SQL)
    -- %L : remplace la valeur, de manière sécurisée
    RETURN 1;
END;
' LANGUAGE 'plpgsql';
