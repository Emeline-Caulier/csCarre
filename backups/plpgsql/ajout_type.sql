create or replace function ajout_type(p_nom_type text) returns integer
as
'
	declare retour integer;

	begin 
		insert into type (nom_type) values (p_nom_type)
		on conflict (nom_type) do nothing
		returning id_type into retour;

		    IF retour IS NOT NULL
    THEN
        return retour;
    END IF;

    select id_type into retour from type where nom_type = p_nom_type;
    if found
    then
        return -1;
    end if;

    return 0;
	end;
'
language 'plpgsql';