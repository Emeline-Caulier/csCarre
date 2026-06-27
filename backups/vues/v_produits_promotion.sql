-- 1. On supprime l'ancienne version
DROP VIEW IF EXISTS v_produits_promotion;

-- 2. On recrée la vue avec les types numériques propres
CREATE VIEW v_produits_promotion AS 
SELECT 
    p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix::numeric AS prix_origine, 
    (p.prix::numeric * (1 - prom.taux_reduction))::numeric(10,2) AS prix_reduit, 
    round((prom.taux_reduction * 100)::numeric, 2) AS reduction_pourcent,
    prom.date_debut,
    prom.date_fin,
    p.stock,
    prom.id_promotion
FROM produit p
JOIN categorie c ON p.id_categorie = c.id_categorie
JOIN promotion prom ON p.id_produit = prom.id_produit
WHERE CURRENT_DATE BETWEEN prom.date_debut AND prom.date_fin;