CREATE OR REPLACE FUNCTION creer_commande(
    p_id_client INT,
    p_id_panier INT,
    p_total     NUMERIC
) RETURNS INT AS $$
DECLARE
    v_id_adresse  INT;
    v_id_commande INT;
    v_nb_items    INT;
    v_stock_ko    INT;
BEGIN
    SELECT id_adresse INTO v_id_adresse
    FROM adresse WHERE id_client = p_id_client LIMIT 1;

    IF v_id_adresse IS NULL THEN RETURN NULL; END IF;

    SELECT COUNT(*) INTO v_nb_items FROM panier_produit WHERE id_panier = p_id_panier;
    IF v_nb_items = 0 THEN RETURN NULL; END IF;

    -- Vérifie que chaque ligne du panier est couverte par le stock disponible
    SELECT COUNT(*) INTO v_stock_ko
    FROM panier_produit pp
    JOIN produit p ON pp.id_produit = p.id_produit
    WHERE pp.id_panier = p_id_panier AND pp.quantite > p.stock;
    IF v_stock_ko > 0 THEN RETURN NULL; END IF;

    INSERT INTO commande (id_client, id_adresse, date_commande, total_commande, statut_paiement, statut_commande)
    VALUES (p_id_client, v_id_adresse, NOW(), p_total, false, 'en_attente')
    RETURNING id_commande INTO v_id_commande;

    INSERT INTO commande_produit (id_commande, id_produit, quantite_commandee, prix_unitaire_achat)
    SELECT v_id_commande,
           pp.id_produit,
           pp.quantite,
           COALESCE(vp.prix_reduit::numeric, p.prix::numeric)
    FROM panier_produit pp
    JOIN produit p ON pp.id_produit = p.id_produit
    LEFT JOIN v_produits_promotion vp ON vp.id_produit = p.id_produit
    WHERE pp.id_panier = p_id_panier;

    -- Décrémente le stock pour chaque produit acheté
    UPDATE produit p
    SET stock = p.stock - pp.quantite
    FROM panier_produit pp
    WHERE pp.id_panier = p_id_panier AND pp.id_produit = p.id_produit;

    DELETE FROM panier_produit WHERE id_panier = p_id_panier;

    RETURN v_id_commande;
END;
$$ LANGUAGE plpgsql;
