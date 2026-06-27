CREATE OR REPLACE FUNCTION ajout_produit(p_nom text, p_stock int, p_prix numeric, p_descr text, p_categorie int)
RETURNS integer
AS '
    declare retour integer;
begin
    INSERT INTO produit (nom_produit, stock, prix, description, id_categorie)
    VALUES (p_nom, p_stock, p_prix, p_descr, p_categorie)
    ON CONFLICT (nom_produit) DO NOTHING
    RETURNING id_produit INTO retour;

    IF retour IS NOT NULL THEN
        return retour;
    END IF;

    return -1;
end;
' language 'plpgsql';
