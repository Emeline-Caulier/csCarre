CREATE OR REPLACE VIEW public.v_produits_hors_stock
 AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    p.stock
   FROM produit p
     JOIN categorie c ON p.id_categorie = c.id_categorie
  WHERE p.stock = 0
  ORDER BY c.nom_categorie, p.nom_produit;

