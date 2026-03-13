CREATE OR REPLACE VIEW public.v_avis_moyennes_produits
 AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    round(avg(a.note_etoiles), 2) AS note_moyenne,
    count(a.id_avis) AS nombre_avis
   FROM produit p
     JOIN categorie c ON p.id_categorie = c.id_categorie
     LEFT JOIN avis a ON p.id_produit = a.id_produit AND a.modere = 'approuvé'::text
  GROUP BY p.id_produit, p.nom_produit, c.nom_categorie
  ORDER BY (round(avg(a.note_etoiles), 2)) DESC NULLS LAST;
