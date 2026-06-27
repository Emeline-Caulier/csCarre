CREATE OR REPLACE VIEW public.v_donnees_client AS
SELECT DISTINCT ON (c.id_client)
    c.id_client,
    c.nom_client,
    c.prenom_client,
    c.email_client,
    a.id_adresse,
    a.rue,
    a.numero,
    a.code_postal,
    a.ville,
    a.pays
FROM public.client c
LEFT JOIN public.adresse a ON c.id_client = a.id_client
ORDER BY c.id_client, c.nom_client ASC;
