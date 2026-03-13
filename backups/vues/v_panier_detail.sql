

CREATE OR REPLACE VIEW public.v_panier_detail
 AS
 SELECT p.id_panier,
    p.id_session,
    p.id_client,
    pr.id_produit,
    pr.nom_produit,
    pp.quantite,
    pr.prix,
    (pr.prix::numeric * pp.quantite::numeric)::money AS sous_total
   FROM panier p
     JOIN panier_produit pp ON p.id_panier = pp.id_panier
     JOIN produit pr ON pp.id_produit = pr.id_produit
  ORDER BY p.id_panier, pr.nom_produit;


