CREATE or replace VIEW v_produits_avec_premiere_image AS
SELECT 
    p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    p.stock,
    p.description,
    (SELECT url_image FROM image_produit 
     WHERE id_produit = p.id_produit 
     ORDER BY ordre LIMIT 1) AS url_image
FROM produit p
JOIN categorie c ON p.id_categorie = c.id_categorie
ORDER BY p.id_produit;