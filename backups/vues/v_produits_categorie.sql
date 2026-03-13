
CREATE OR REPLACE VIEW public.v_produits_categorie
 AS
 SELECT p.id_produit,
    p.nom_produit,
    c.id_categorie,
    c.nom_categorie,
    p.prix,
    p.stock,
    p.description,
    img.url_image,
    img.ordre
   FROM produit p
     JOIN categorie c ON p.id_categorie = c.id_categorie
     LEFT JOIN image_produit img ON p.id_produit = img.id_produit
  ORDER BY p.id_produit, img.ordre;

