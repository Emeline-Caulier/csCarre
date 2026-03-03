create or replace function ajout_pays(p_nom_pays text, p_is_code text) returns integer
as
'
  declare retour int;
  begin
    insert into pays (nom_pays,iso_code) values
    (p_nom_pays,p_is_code)
    ON CONFLICT (nom_pays) DO NOTHING
    RETURNING id_pays INTO retour;

    IF retour IS NOT NULL
    THEN
        return retour;
    END IF;

    select id_pays into retour from pays where nom_pays = p_nom_pays;
    if found
    then
        return -1;
    end if;

    return 0;
  end;

' language 'plpgsql';