CREATE OR REPLACE VIEW v_avis_details AS
SELECT 
    a.id_avis,
    c.prenom_client,
    c.nom_client,
    p.nom_produit,
    a.note_etoiles,
    a.commentaire,
    a.date_avis,
    a.photo,       
    a.modere        
FROM public.avis a
JOIN public.client c ON a.id_client = c.id_client
JOIN public.produit p ON a.id_produit = p.id_produit;