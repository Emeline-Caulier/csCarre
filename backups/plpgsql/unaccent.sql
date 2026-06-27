CREATE OR REPLACE FUNCTION unaccent(
	regdictionary,
	text)
    RETURNS text
    LANGUAGE 'c'
    COST 1
    STABLE STRICT PARALLEL SAFE 
AS '$libdir/unaccent', 'unaccent_dict'
;

ALTER FUNCTION public.unaccent(regdictionary, text)
    OWNER TO postgres;

ALTER FUNCTION public.unaccent(regdictionary, text)
    DEPENDS ON EXTENSION unaccent;

