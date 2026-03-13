CREATE OR REPLACE VIEW v_produits_promotion AS
SELECT 
    p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    (p.prix::numeric * (1 - prom.taux_reduction))::money AS prix_reduit,
    ROUND((prom.taux_reduction * 100)::numeric, 2) AS reduction_pourcent,
    prom.date_debut,
    prom.date_fin,
    p.stock,
    prom.id_promotion
FROM produit p
JOIN categorie c ON p.id_categorie = c.id_categorie
JOIN promotion prom ON p.id_produit = prom.id_produit
WHERE CURRENT_DATE BETWEEN prom.date_debut AND prom.date_fin
    AND prom.id_promotion = (
        SELECT id_promotion FROM promotion 
        WHERE id_produit = p.id_produit 
            AND CURRENT_DATE BETWEEN date_debut AND date_fin
        ORDER BY date_debut DESC 
        LIMIT 1
    )
ORDER BY reduction_pourcent DESC;