CREATE OR REPLACE VIEW public.v_avis_approuves
 AS
 SELECT a.id_avis,
    p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    cl.nom_client,
    cl.prenom_client,
    a.note_etoiles,
    a.commentaire,
    a.date_avis,
    a.photo
   FROM avis a
     JOIN produit p ON a.id_produit = p.id_produit
     JOIN categorie c ON p.id_categorie = c.id_categorie
     JOIN client cl ON a.id_client = cl.id_client
  WHERE a.modere = 'approuvé'::text
  ORDER BY a.date_avis DESC;

