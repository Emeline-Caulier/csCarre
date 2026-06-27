--
-- PostgreSQL database dump
--

\restrict bygsRL1wCwTGVPvgxXzbcn0eXId9p6Rz6imj4TgAoN3z8PJR6eAUnpSe3JBao0s

-- Dumped from database version 18.4 (Debian 18.4-1.pgdg13+1)
-- Dumped by pg_dump version 18.4 (Debian 18.4-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.promotion DROP CONSTRAINT IF EXISTS fk_promotion_produit;
ALTER TABLE IF EXISTS ONLY public.produit DROP CONSTRAINT IF EXISTS fk_produit_categorie;
ALTER TABLE IF EXISTS ONLY public.panier_produit DROP CONSTRAINT IF EXISTS fk_panier_produit_produit;
ALTER TABLE IF EXISTS ONLY public.panier_produit DROP CONSTRAINT IF EXISTS fk_panier_produit_panier;
ALTER TABLE IF EXISTS ONLY public.panier DROP CONSTRAINT IF EXISTS fk_panier_client;
ALTER TABLE IF EXISTS ONLY public.message_contact DROP CONSTRAINT IF EXISTS fk_message_commande;
ALTER TABLE IF EXISTS ONLY public.message_contact DROP CONSTRAINT IF EXISTS fk_message_client;
ALTER TABLE IF EXISTS ONLY public.liste_envie DROP CONSTRAINT IF EXISTS fk_liste_envie_produit;
ALTER TABLE IF EXISTS ONLY public.liste_envie DROP CONSTRAINT IF EXISTS fk_liste_envie_client;
ALTER TABLE IF EXISTS ONLY public.image_produit DROP CONSTRAINT IF EXISTS fk_image_produit;
ALTER TABLE IF EXISTS ONLY public.commande_produit DROP CONSTRAINT IF EXISTS fk_commande_produit_produit;
ALTER TABLE IF EXISTS ONLY public.commande_produit DROP CONSTRAINT IF EXISTS fk_commande_produit_commande;
ALTER TABLE IF EXISTS ONLY public.commande DROP CONSTRAINT IF EXISTS fk_commande_client;
ALTER TABLE IF EXISTS ONLY public.commande DROP CONSTRAINT IF EXISTS fk_commande_adresse;
ALTER TABLE IF EXISTS ONLY public.avis DROP CONSTRAINT IF EXISTS fk_avis_produit;
ALTER TABLE IF EXISTS ONLY public.avis DROP CONSTRAINT IF EXISTS fk_avis_commande;
ALTER TABLE IF EXISTS ONLY public.avis DROP CONSTRAINT IF EXISTS fk_avis_client;
ALTER TABLE IF EXISTS ONLY public.adresse DROP CONSTRAINT IF EXISTS fk_adresse_client;
DROP INDEX IF EXISTS public.unique_avis_commande_produit;
ALTER TABLE IF EXISTS ONLY public.produit DROP CONSTRAINT IF EXISTS uq_nom_produit;
ALTER TABLE IF EXISTS ONLY public.promotion DROP CONSTRAINT IF EXISTS promotion_pkey;
ALTER TABLE IF EXISTS ONLY public.produit DROP CONSTRAINT IF EXISTS produit_pkey;
ALTER TABLE IF EXISTS ONLY public.panier_produit DROP CONSTRAINT IF EXISTS panier_produit_pkey;
ALTER TABLE IF EXISTS ONLY public.panier DROP CONSTRAINT IF EXISTS panier_pkey;
ALTER TABLE IF EXISTS ONLY public.panier DROP CONSTRAINT IF EXISTS panier_id_session_key;
ALTER TABLE IF EXISTS ONLY public.message_contact DROP CONSTRAINT IF EXISTS message_contact_pkey;
ALTER TABLE IF EXISTS ONLY public.liste_envie DROP CONSTRAINT IF EXISTS liste_envie_pkey;
ALTER TABLE IF EXISTS ONLY public.image_produit DROP CONSTRAINT IF EXISTS image_produit_url_image_key;
ALTER TABLE IF EXISTS ONLY public.image_produit DROP CONSTRAINT IF EXISTS image_produit_pkey;
ALTER TABLE IF EXISTS ONLY public.image_produit DROP CONSTRAINT IF EXISTS image_produit_ordre_key;
ALTER TABLE IF EXISTS ONLY public.configuration DROP CONSTRAINT IF EXISTS configuration_pkey;
ALTER TABLE IF EXISTS ONLY public.commande_produit DROP CONSTRAINT IF EXISTS commande_produit_pkey;
ALTER TABLE IF EXISTS ONLY public.commande DROP CONSTRAINT IF EXISTS commande_pkey;
ALTER TABLE IF EXISTS ONLY public.client DROP CONSTRAINT IF EXISTS client_pkey;
ALTER TABLE IF EXISTS ONLY public.client DROP CONSTRAINT IF EXISTS client_email_client_key;
ALTER TABLE IF EXISTS ONLY public.categorie DROP CONSTRAINT IF EXISTS categorie_pkey;
ALTER TABLE IF EXISTS ONLY public.categorie DROP CONSTRAINT IF EXISTS categorie_nom_categorie_key;
ALTER TABLE IF EXISTS ONLY public.avis DROP CONSTRAINT IF EXISTS avis_pkey;
ALTER TABLE IF EXISTS ONLY public.adresse DROP CONSTRAINT IF EXISTS adresse_pkey;
ALTER TABLE IF EXISTS ONLY public.admin DROP CONSTRAINT IF EXISTS admin_pkey;
ALTER TABLE IF EXISTS ONLY public.admin DROP CONSTRAINT IF EXISTS admin_email_admin_key;
ALTER TABLE IF EXISTS public.produit_2 ALTER COLUMN id_produit DROP DEFAULT;
DROP VIEW IF EXISTS public.v_produits_promotion;
DROP VIEW IF EXISTS public.v_messages_contact;
DROP VIEW IF EXISTS public.v_donnees_client;
DROP VIEW IF EXISTS public.v_commandes_client;
DROP VIEW IF EXISTS public.v_avis_details;
DROP VIEW IF EXISTS public.v_avis_approuves;
DROP TABLE IF EXISTS public.promotion;
DROP SEQUENCE IF EXISTS public.produit_2_id_produit_seq;
DROP TABLE IF EXISTS public.produit_2;
DROP TABLE IF EXISTS public.produit;
DROP TABLE IF EXISTS public.panier_produit;
DROP TABLE IF EXISTS public.panier;
DROP TABLE IF EXISTS public.message_contact;
DROP TABLE IF EXISTS public.liste_envie;
DROP TABLE IF EXISTS public.image_produit;
DROP TABLE IF EXISTS public.configuration;
DROP TABLE IF EXISTS public.commande_produit;
DROP TABLE IF EXISTS public.commande;
DROP TABLE IF EXISTS public.client;
DROP TABLE IF EXISTS public.categorie;
DROP SEQUENCE IF EXISTS public.categorie_id_seq;
DROP TABLE IF EXISTS public.avis;
DROP TABLE IF EXISTS public.adresse;
DROP TABLE IF EXISTS public.admin;
DROP FUNCTION IF EXISTS public.update_statut_message(p_id integer, p_statut text);
DROP FUNCTION IF EXISTS public.update_statut_commande(p_id integer, p_statut text);
DROP FUNCTION IF EXISTS public.update_statut_avis(p_id integer, p_statut text);
DROP FUNCTION IF EXISTS public.update_quantite_panier(p_id_panier integer, p_id_produit integer, p_quantite integer);
DROP FUNCTION IF EXISTS public.update_profil_client(p_id integer, p_nom text, p_prenom text, p_email text);
DROP FUNCTION IF EXISTS public.update_produit(p_id integer, p_id_categorie integer, p_nom text, p_description text, p_prix numeric, p_stock integer, p_est_nouveau boolean);
DROP FUNCTION IF EXISTS public.update_password_client(p_id integer, p_mdp text);
DROP FUNCTION IF EXISTS public.update_paiement_commande(p_id integer, p_statut boolean);
DROP FUNCTION IF EXISTS public.update_champ_promotion(p_id_promotion integer, p_champ text, p_valeur text);
DROP FUNCTION IF EXISTS public.update_champ_produit(p_champ text, p_valeur text, p_id integer);
DROP FUNCTION IF EXISTS public.update_categorie(p_id integer, p_nom text);
DROP FUNCTION IF EXISTS public.update_adresse_client(p_id integer, p_rue text, p_num text, p_cp text, p_ville text, p_pays text);
DROP FUNCTION IF EXISTS public.toggle_liste_envie(p_id_session text, p_id_produit integer, p_id_client integer);
DROP FUNCTION IF EXISTS public.supprimer_produit_panier(p_id_panier integer, p_id_produit integer);
DROP FUNCTION IF EXISTS public.set_est_nouveau(p_id integer, p_val boolean);
DROP FUNCTION IF EXISTS public.retirer_de_la_vitrine(p_id_produit integer);
DROP FUNCTION IF EXISTS public.lier_client_liste_envie(p_id_session text, p_id_client integer);
DROP FUNCTION IF EXISTS public.get_ou_creer_panier(p_id_session text, p_id_client integer);
DROP FUNCTION IF EXISTS public.get_admin(p_email_admin text, p_mot_de_passe text);
DROP FUNCTION IF EXISTS public.delete_promotion(p_id_promotion integer);
DROP FUNCTION IF EXISTS public.delete_produit(p_id integer);
DROP FUNCTION IF EXISTS public.delete_image_produit(p_id_image integer);
DROP FUNCTION IF EXISTS public.delete_categorie(p_id integer);
DROP FUNCTION IF EXISTS public.creer_commande(p_id_client integer, p_id_panier integer, p_total numeric);
DROP FUNCTION IF EXISTS public.ajouter_produit_panier(p_id_panier integer, p_id_produit integer, p_quantite integer);
DROP FUNCTION IF EXISTS public.ajout_promotion(p_id_produit integer, p_taux numeric, p_date_debut date, p_date_fin date);
DROP FUNCTION IF EXISTS public.ajout_produit(p_nom text, p_stock integer, p_prix numeric, p_descr text, p_categorie integer);
DROP FUNCTION IF EXISTS public.ajout_message(p_id_client integer, p_nom text, p_email text, p_num_commande integer, p_sujet text, p_contenu text);
DROP FUNCTION IF EXISTS public.ajout_image_produit(p_id_produit integer, p_url text);
DROP FUNCTION IF EXISTS public.ajout_client(p_email text, p_password text, p_nom text, p_prenom text, p_rue text, p_numero text, p_cp text, p_ville text, p_pays text);
DROP FUNCTION IF EXISTS public.ajout_categorie(p_nom text);
DROP FUNCTION IF EXISTS public.ajout_avis(p_id_client integer, p_id_commande integer, p_id_produit integer, p_note integer, p_commentaire text, p_photo text);
DROP EXTENSION IF EXISTS unaccent;
-- *not* dropping schema, since initdb creates it
--
-- Name: public; Type: SCHEMA; Schema: -; Owner: anonyme
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO anonyme;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: anonyme
--

COMMENT ON SCHEMA public IS '';


--
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


--
-- Name: ajout_avis(integer, integer, integer, integer, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_avis(p_id_client integer, p_id_commande integer, p_id_produit integer, p_note integer, p_commentaire text, p_photo text DEFAULT NULL::text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO avis (id_client, id_commande, id_produit, note_etoiles, commentaire, date_avis, photo)
    VALUES (p_id_client, p_id_commande, p_id_produit, p_note, p_commentaire, CURRENT_DATE, p_photo);
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$;


ALTER FUNCTION public.ajout_avis(p_id_client integer, p_id_commande integer, p_id_produit integer, p_note integer, p_commentaire text, p_photo text) OWNER TO anonyme;

--
-- Name: ajout_categorie(text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_categorie(p_nom text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE v_id INT;
BEGIN
    SELECT id_categorie INTO v_id FROM categorie WHERE nom_categorie = p_nom;
    IF FOUND THEN
        RETURN -1;
    END IF;
    INSERT INTO categorie (nom_categorie) VALUES (p_nom);
    SELECT id_categorie INTO v_id FROM categorie WHERE nom_categorie = p_nom;
    RETURN v_id;
EXCEPTION WHEN OTHERS THEN
    RETURN 0;
END;
$$;


ALTER FUNCTION public.ajout_categorie(p_nom text) OWNER TO anonyme;

--
-- Name: ajout_client(text, text, text, text, text, text, text, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_client(p_email text, p_password text, p_nom text, p_prenom text, p_rue text, p_numero text, p_cp text, p_ville text, p_pays text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE 
    v_id_client int;
BEGIN
    INSERT INTO public.client (nom_client, prenom_client, email_client, mot_de_passe)
    VALUES (p_nom, p_prenom, p_email, p_password)
    ON CONFLICT (email_client) DO NOTHING 
    RETURNING id_client INTO v_id_client;

    IF v_id_client IS NOT NULL THEN
        INSERT INTO public.adresse (id_client, rue, numero, code_postal, ville, pays)
        VALUES (v_id_client, p_rue, p_numero, p_cp, p_ville, p_pays);
        
        RETURN v_id_client; 
    END IF;

    SELECT id_client INTO v_id_client FROM public.client WHERE email_client = p_email;
    IF FOUND THEN
        RETURN -1;
    END IF;

    RETURN 0;
END;
$$;


ALTER FUNCTION public.ajout_client(p_email text, p_password text, p_nom text, p_prenom text, p_rue text, p_numero text, p_cp text, p_ville text, p_pays text) OWNER TO anonyme;

--
-- Name: ajout_image_produit(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_image_produit(p_id_produit integer, p_url text) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO image_produit (id_image, id_produit, url_image, ordre)
    SELECT COALESCE(MAX(id_image), 0) + 1, p_id_produit, p_url, COALESCE(MAX(ordre), 0) + 1
    FROM image_produit;
END;
$$;


ALTER FUNCTION public.ajout_image_produit(p_id_produit integer, p_url text) OWNER TO anonyme;

--
-- Name: ajout_message(integer, text, text, integer, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_message(p_id_client integer, p_nom text, p_email text, p_num_commande integer, p_sujet text, p_contenu text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO message_contact (id_client, nom_contact, email_contact, num_commande, sujet, contenu, date_message, statut)
    VALUES (p_id_client, p_nom, p_email, p_num_commande, p_sujet, p_contenu, NOW(), 'non_lu');
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$;


ALTER FUNCTION public.ajout_message(p_id_client integer, p_nom text, p_email text, p_num_commande integer, p_sujet text, p_contenu text) OWNER TO anonyme;

--
-- Name: ajout_produit(text, integer, numeric, text, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_produit(p_nom text, p_stock integer, p_prix numeric, p_descr text, p_categorie integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
    declare retour integer;
begin
    INSERT INTO produit (nom_produit, stock, prix, description, id_categorie)
    VALUES (p_nom, p_stock, p_prix, p_descr, p_categorie)
    ON CONFLICT (nom_produit) DO NOTHING
    RETURNING id_produit INTO retour;

    IF retour IS NOT NULL THEN
        return retour;
    END IF;

    return -1;
end;
$$;


ALTER FUNCTION public.ajout_produit(p_nom text, p_stock integer, p_prix numeric, p_descr text, p_categorie integer) OWNER TO anonyme;

--
-- Name: ajout_promotion(integer, numeric, date, date); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajout_promotion(p_id_produit integer, p_taux numeric, p_date_debut date, p_date_fin date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO promotion (id_produit, taux_reduction, date_debut, date_fin)
    VALUES (p_id_produit, p_taux, p_date_debut, p_date_fin);
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$;


ALTER FUNCTION public.ajout_promotion(p_id_produit integer, p_taux numeric, p_date_debut date, p_date_fin date) OWNER TO anonyme;

--
-- Name: ajouter_produit_panier(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.ajouter_produit_panier(p_id_panier integer, p_id_produit integer, p_quantite integer DEFAULT 1) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_stock         INT;
    v_qte_actuelle  INT;
BEGIN
    -- Stock disponible du produit
    SELECT stock INTO v_stock FROM produit WHERE id_produit = p_id_produit;
    IF v_stock IS NULL THEN
        RETURN FALSE;
    END IF;

    -- Quantité déjà présente dans le panier (0 si absent)
    SELECT COALESCE(quantite, 0) INTO v_qte_actuelle
    FROM panier_produit
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    v_qte_actuelle := COALESCE(v_qte_actuelle, 0);

    -- Refus si la quantité demandée dépasse le stock disponible
    IF v_qte_actuelle + p_quantite > v_stock THEN
        RETURN FALSE;
    END IF;

    IF v_qte_actuelle > 0 THEN
        UPDATE panier_produit
        SET quantite = quantite + p_quantite
        WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    ELSE
        INSERT INTO panier_produit (id_panier, id_produit, quantite)
        VALUES (p_id_panier, p_id_produit, p_quantite);
    END IF;
    RETURN TRUE;
EXCEPTION WHEN OTHERS THEN
    RETURN FALSE;
END;
$$;


ALTER FUNCTION public.ajouter_produit_panier(p_id_panier integer, p_id_produit integer, p_quantite integer) OWNER TO anonyme;

--
-- Name: creer_commande(integer, integer, numeric); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.creer_commande(p_id_client integer, p_id_panier integer, p_total numeric) RETURNS integer
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION public.creer_commande(p_id_client integer, p_id_panier integer, p_total numeric) OWNER TO anonyme;

--
-- Name: delete_categorie(integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.delete_categorie(p_id integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM categorie WHERE id_categorie = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.delete_categorie(p_id integer) OWNER TO anonyme;

--
-- Name: delete_image_produit(integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.delete_image_produit(p_id_image integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM image_produit WHERE id_image = p_id_image;
END;
$$;


ALTER FUNCTION public.delete_image_produit(p_id_image integer) OWNER TO anonyme;

--
-- Name: delete_produit(integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.delete_produit(p_id integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM produit WHERE id_produit = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.delete_produit(p_id integer) OWNER TO anonyme;

--
-- Name: delete_promotion(integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.delete_promotion(p_id_promotion integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM promotion WHERE id_promotion = p_id_promotion;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.delete_promotion(p_id_promotion integer) OWNER TO anonyme;

--
-- Name: get_admin(text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.get_admin(p_email_admin text, p_mot_de_passe text) RETURNS TABLE(id_admin integer, nom_admin text, prenom_admin text, email_admin text)
    LANGUAGE plpgsql STABLE
    AS $$
BEGIN
    RETURN QUERY 
    SELECT a.id_admin, a.nom_admin, a.prenom_admin, a.email_admin 
    FROM admin a 
    WHERE a.email_admin = p_email_admin
      AND a.mot_de_passe = p_mot_de_passe;
END;
$$;


ALTER FUNCTION public.get_admin(p_email_admin text, p_mot_de_passe text) OWNER TO anonyme;

--
-- Name: get_ou_creer_panier(text, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.get_ou_creer_panier(p_id_session text, p_id_client integer DEFAULT NULL::integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_id_panier INT;
BEGIN
    SELECT id_panier INTO v_id_panier
    FROM panier WHERE id_session = p_id_session LIMIT 1;

    IF v_id_panier IS NOT NULL THEN
        IF p_id_client IS NOT NULL THEN
            UPDATE panier SET id_client = p_id_client
            WHERE id_panier = v_id_panier AND id_client IS NULL;
        END IF;
        RETURN v_id_panier;
    ELSE
        INSERT INTO panier (id_session, id_client)
        VALUES (p_id_session, p_id_client)
        RETURNING id_panier INTO v_id_panier;
        RETURN v_id_panier;
    END IF;
END;
$$;


ALTER FUNCTION public.get_ou_creer_panier(p_id_session text, p_id_client integer) OWNER TO anonyme;

--
-- Name: lier_client_liste_envie(text, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.lier_client_liste_envie(p_id_session text, p_id_client integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM liste_envie
    WHERE id_session = p_id_session
      AND id_client IS NULL
      AND id_produit IN (
          SELECT id_produit FROM liste_envie WHERE id_client = p_id_client
      );

    UPDATE liste_envie
    SET id_client = p_id_client
    WHERE id_session = p_id_session AND id_client IS NULL;
END;
$$;


ALTER FUNCTION public.lier_client_liste_envie(p_id_session text, p_id_client integer) OWNER TO anonyme;

--
-- Name: retirer_de_la_vitrine(integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.retirer_de_la_vitrine(p_id_produit integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE produit SET en_vitrine = false WHERE id_produit = p_id_produit;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.retirer_de_la_vitrine(p_id_produit integer) OWNER TO anonyme;

--
-- Name: set_est_nouveau(integer, boolean); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.set_est_nouveau(p_id integer, p_val boolean) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE produit SET est_nouveau = p_val WHERE id_produit = p_id;
END;
$$;


ALTER FUNCTION public.set_est_nouveau(p_id integer, p_val boolean) OWNER TO anonyme;

--
-- Name: supprimer_produit_panier(integer, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.supprimer_produit_panier(p_id_panier integer, p_id_produit integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    DELETE FROM panier_produit
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.supprimer_produit_panier(p_id_panier integer, p_id_produit integer) OWNER TO anonyme;

--
-- Name: toggle_liste_envie(text, integer, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.toggle_liste_envie(p_id_session text, p_id_produit integer, p_id_client integer DEFAULT NULL::integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_id_liste INT;
BEGIN
    IF p_id_client IS NOT NULL THEN
        SELECT id_liste_envie INTO v_id_liste
        FROM liste_envie
        WHERE id_client = p_id_client AND id_produit = p_id_produit
        LIMIT 1;
    ELSE
        SELECT id_liste_envie INTO v_id_liste
        FROM liste_envie
        WHERE id_session = p_id_session AND id_client IS NULL AND id_produit = p_id_produit
        LIMIT 1;
    END IF;

    IF v_id_liste IS NOT NULL THEN
        DELETE FROM liste_envie WHERE id_liste_envie = v_id_liste;
        RETURN FALSE;
    ELSE
        INSERT INTO liste_envie (id_session, id_client, id_produit, date_ajout)
        VALUES (p_id_session, p_id_client, p_id_produit, CURRENT_DATE);
        RETURN TRUE;
    END IF;
END;
$$;


ALTER FUNCTION public.toggle_liste_envie(p_id_session text, p_id_produit integer, p_id_client integer) OWNER TO anonyme;

--
-- Name: update_adresse_client(integer, text, text, text, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_adresse_client(p_id integer, p_rue text, p_num text, p_cp text, p_ville text, p_pays text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE adresse
    SET rue         = p_rue,
        numero      = p_num,
        code_postal = p_cp,
        ville       = p_ville,
        pays        = p_pays
    WHERE id_client = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_adresse_client(p_id integer, p_rue text, p_num text, p_cp text, p_ville text, p_pays text) OWNER TO anonyme;

--
-- Name: update_categorie(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_categorie(p_id integer, p_nom text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE categorie SET nom_categorie = p_nom WHERE id_categorie = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_categorie(p_id integer, p_nom text) OWNER TO anonyme;

--
-- Name: update_champ_produit(text, text, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_champ_produit(p_champ text, p_valeur text, p_id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
BEGIN
    EXECUTE format('UPDATE produit SET %I = %L WHERE id_produit = %L', p_champ, p_valeur, p_id);
    -- execute format : utilisé lorsque les champs sont dynamiques
    -- %I : remplace le nom de colonne, de manière sécurisée (échappement pour éviter les injections SQL)
    -- %L : remplace la valeur, de manière sécurisée
    RETURN 1;
END;
$$;


ALTER FUNCTION public.update_champ_produit(p_champ text, p_valeur text, p_id integer) OWNER TO anonyme;

--
-- Name: update_champ_promotion(integer, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_champ_promotion(p_id_promotion integer, p_champ text, p_valeur text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF p_champ = 'taux_reduction' THEN
        UPDATE promotion SET taux_reduction = p_valeur::NUMERIC WHERE id_promotion = p_id_promotion;
    ELSIF p_champ = 'date_debut' THEN
        UPDATE promotion SET date_debut = p_valeur::DATE WHERE id_promotion = p_id_promotion;
    ELSIF p_champ = 'date_fin' THEN
        UPDATE promotion SET date_fin = p_valeur::DATE WHERE id_promotion = p_id_promotion;
    ELSE
        RETURN FALSE;
    END IF;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_champ_promotion(p_id_promotion integer, p_champ text, p_valeur text) OWNER TO anonyme;

--
-- Name: update_paiement_commande(integer, boolean); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_paiement_commande(p_id integer, p_statut boolean) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE commande SET statut_paiement = p_statut WHERE id_commande = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_paiement_commande(p_id integer, p_statut boolean) OWNER TO anonyme;

--
-- Name: update_password_client(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_password_client(p_id integer, p_mdp text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE client SET mot_de_passe = p_mdp WHERE id_client = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_password_client(p_id integer, p_mdp text) OWNER TO anonyme;

--
-- Name: update_produit(integer, integer, text, text, numeric, integer, boolean); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_produit(p_id integer, p_id_categorie integer, p_nom text, p_description text, p_prix numeric, p_stock integer, p_est_nouveau boolean) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE produit
    SET id_categorie = p_id_categorie,
        nom_produit  = p_nom,
        description  = p_description,
        prix         = p_prix,
        stock        = p_stock,
        est_nouveau  = p_est_nouveau
    WHERE id_produit = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_produit(p_id integer, p_id_categorie integer, p_nom text, p_description text, p_prix numeric, p_stock integer, p_est_nouveau boolean) OWNER TO anonyme;

--
-- Name: update_profil_client(integer, text, text, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_profil_client(p_id integer, p_nom text, p_prenom text, p_email text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE client
    SET nom_client    = p_nom,
        prenom_client = p_prenom,
        email_client  = p_email
    WHERE id_client = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_profil_client(p_id integer, p_nom text, p_prenom text, p_email text) OWNER TO anonyme;

--
-- Name: update_quantite_panier(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_quantite_panier(p_id_panier integer, p_id_produit integer, p_quantite integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_stock INT;
BEGIN
    -- Refus si la quantité demandée dépasse le stock disponible
    SELECT stock INTO v_stock FROM produit WHERE id_produit = p_id_produit;
    IF v_stock IS NULL OR p_quantite > v_stock THEN
        RETURN FALSE;
    END IF;

    UPDATE panier_produit
    SET quantite = p_quantite
    WHERE id_panier = p_id_panier AND id_produit = p_id_produit;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_quantite_panier(p_id_panier integer, p_id_produit integer, p_quantite integer) OWNER TO anonyme;

--
-- Name: update_statut_avis(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_statut_avis(p_id integer, p_statut text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE avis SET modere = p_statut WHERE id_avis = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_statut_avis(p_id integer, p_statut text) OWNER TO anonyme;

--
-- Name: update_statut_commande(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_statut_commande(p_id integer, p_statut text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF p_statut NOT IN ('en_attente', 'approuvée', 'envoyée', 'annulée') THEN
        RETURN FALSE;
    END IF;
    UPDATE commande SET statut_commande = p_statut WHERE id_commande = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_statut_commande(p_id integer, p_statut text) OWNER TO anonyme;

--
-- Name: update_statut_message(integer, text); Type: FUNCTION; Schema: public; Owner: anonyme
--

CREATE FUNCTION public.update_statut_message(p_id integer, p_statut text) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE message_contact SET statut = p_statut WHERE id_message = p_id;
    RETURN FOUND;
END;
$$;


ALTER FUNCTION public.update_statut_message(p_id integer, p_statut text) OWNER TO anonyme;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: admin; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.admin (
    id_admin integer NOT NULL,
    nom_admin text NOT NULL,
    prenom_admin text NOT NULL,
    email_admin text NOT NULL,
    mot_de_passe text NOT NULL
);


ALTER TABLE public.admin OWNER TO anonyme;

--
-- Name: admin_id_admin_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.admin ALTER COLUMN id_admin ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.admin_id_admin_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: adresse; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.adresse (
    id_adresse integer NOT NULL,
    id_client integer NOT NULL,
    rue text NOT NULL,
    numero text NOT NULL,
    code_postal text NOT NULL,
    ville text NOT NULL,
    pays text NOT NULL,
    CONSTRAINT adresse_pays_check CHECK ((pays = ANY (ARRAY['Belgique'::text, 'France'::text, 'Allemagne'::text, 'Pays-Bas'::text, 'Luxembourg'::text, 'Espagne'::text, 'Italie'::text, 'Portugal'::text, 'Suisse'::text, 'Autriche'::text, 'Pologne'::text, 'République Tchèque'::text, 'Slovaquie'::text, 'Slovénie'::text, 'Croatie'::text, 'Hongrie'::text, 'Roumanie'::text, 'Bulgarie'::text, 'Grèce'::text, 'Lettonie'::text, 'Lituanie'::text, 'Estonie'::text, 'Finlande'::text, 'Suède'::text, 'Norvège'::text, 'Danemark'::text])))
);


ALTER TABLE public.adresse OWNER TO anonyme;

--
-- Name: adresse_id_adresse_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.adresse ALTER COLUMN id_adresse ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.adresse_id_adresse_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: avis; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.avis (
    id_avis integer NOT NULL,
    id_client integer NOT NULL,
    id_produit integer NOT NULL,
    note_etoiles integer NOT NULL,
    commentaire text NOT NULL,
    date_avis date NOT NULL,
    modere text DEFAULT 'en_attente'::text NOT NULL,
    photo text,
    id_commande integer,
    CONSTRAINT avis_modere_check CHECK ((modere = ANY (ARRAY['en_attente'::text, 'approuvé'::text, 'refusé'::text]))),
    CONSTRAINT avis_note_etoiles_check CHECK (((note_etoiles >= 1) AND (note_etoiles <= 5)))
);


ALTER TABLE public.avis OWNER TO anonyme;

--
-- Name: avis_id_avis_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.avis ALTER COLUMN id_avis ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.avis_id_avis_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: categorie_id_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

CREATE SEQUENCE public.categorie_id_seq
    START WITH 6
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorie_id_seq OWNER TO anonyme;

--
-- Name: categorie; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.categorie (
    id_categorie integer DEFAULT nextval('public.categorie_id_seq'::regclass) NOT NULL,
    nom_categorie text NOT NULL
);


ALTER TABLE public.categorie OWNER TO anonyme;

--
-- Name: client; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.client (
    id_client integer NOT NULL,
    nom_client text NOT NULL,
    prenom_client text NOT NULL,
    email_client text NOT NULL,
    mot_de_passe text NOT NULL
);


ALTER TABLE public.client OWNER TO anonyme;

--
-- Name: client_id_client_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.client ALTER COLUMN id_client ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.client_id_client_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: commande; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.commande (
    id_commande integer NOT NULL,
    id_client integer NOT NULL,
    id_adresse integer NOT NULL,
    date_commande timestamp without time zone NOT NULL,
    total_commande money NOT NULL,
    statut_paiement boolean NOT NULL,
    statut_commande text DEFAULT 'en_attente'::text NOT NULL,
    CONSTRAINT commande_statut_commande_check CHECK ((statut_commande = ANY (ARRAY['en_attente'::text, 'approuvée'::text, 'annulée'::text, 'envoyée'::text])))
);


ALTER TABLE public.commande OWNER TO anonyme;

--
-- Name: commande_id_commande_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.commande ALTER COLUMN id_commande ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.commande_id_commande_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: commande_produit; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.commande_produit (
    id_commande integer NOT NULL,
    id_produit integer NOT NULL,
    quantite_commandee integer NOT NULL,
    prix_unitaire_achat money NOT NULL
);


ALTER TABLE public.commande_produit OWNER TO anonyme;

--
-- Name: configuration; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.configuration (
    cle text NOT NULL,
    valeur text
);


ALTER TABLE public.configuration OWNER TO anonyme;

--
-- Name: image_produit; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.image_produit (
    id_image integer NOT NULL,
    id_produit integer NOT NULL,
    url_image text NOT NULL,
    ordre integer NOT NULL
);


ALTER TABLE public.image_produit OWNER TO anonyme;

--
-- Name: image_produit_id_image_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.image_produit ALTER COLUMN id_image ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.image_produit_id_image_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: liste_envie; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.liste_envie (
    id_liste_envie integer NOT NULL,
    id_session text NOT NULL,
    id_client integer,
    id_produit integer NOT NULL,
    date_ajout date NOT NULL
);


ALTER TABLE public.liste_envie OWNER TO anonyme;

--
-- Name: liste_envie_id_liste_envie_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.liste_envie ALTER COLUMN id_liste_envie ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.liste_envie_id_liste_envie_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: message_contact; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.message_contact (
    id_message integer NOT NULL,
    id_client integer,
    nom_contact text NOT NULL,
    email_contact text NOT NULL,
    num_commande integer,
    sujet text NOT NULL,
    contenu text NOT NULL,
    photo text,
    date_message timestamp without time zone NOT NULL,
    statut character varying(10) DEFAULT 'non_lu'::character varying NOT NULL,
    reponse text,
    CONSTRAINT chk_message_statut CHECK (((statut)::text = ANY (ARRAY[('non_lu'::character varying)::text, ('lu'::character varying)::text, ('repondu'::character varying)::text])))
);


ALTER TABLE public.message_contact OWNER TO anonyme;

--
-- Name: message_contact_id_message_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.message_contact ALTER COLUMN id_message ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.message_contact_id_message_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: panier; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.panier (
    id_panier integer NOT NULL,
    id_session text NOT NULL,
    id_client integer
);


ALTER TABLE public.panier OWNER TO anonyme;

--
-- Name: panier_id_panier_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.panier ALTER COLUMN id_panier ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.panier_id_panier_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: panier_produit; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.panier_produit (
    id_panier integer NOT NULL,
    id_produit integer NOT NULL,
    quantite integer NOT NULL,
    CONSTRAINT panier_produit_quantite_check CHECK ((quantite >= 1))
);


ALTER TABLE public.panier_produit OWNER TO anonyme;

--
-- Name: produit; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.produit (
    id_produit integer NOT NULL,
    id_categorie integer NOT NULL,
    nom_produit text NOT NULL,
    description text,
    prix money NOT NULL,
    stock integer NOT NULL,
    en_vitrine boolean DEFAULT true NOT NULL,
    est_nouveau boolean DEFAULT false,
    CONSTRAINT produit_stock_check CHECK ((stock >= 0))
);


ALTER TABLE public.produit OWNER TO anonyme;

--
-- Name: produit_2; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.produit_2 (
    id_produit integer NOT NULL,
    id_categorie integer,
    nom_produit text,
    description text,
    prix money,
    stock integer,
    en_vitrine boolean,
    est_nouveau boolean
);


ALTER TABLE public.produit_2 OWNER TO anonyme;

--
-- Name: produit_2_id_produit_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

CREATE SEQUENCE public.produit_2_id_produit_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produit_2_id_produit_seq OWNER TO anonyme;

--
-- Name: produit_2_id_produit_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: anonyme
--

ALTER SEQUENCE public.produit_2_id_produit_seq OWNED BY public.produit_2.id_produit;


--
-- Name: produit_id_produit_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.produit ALTER COLUMN id_produit ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.produit_id_produit_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: promotion; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.promotion (
    id_promotion integer NOT NULL,
    id_produit integer NOT NULL,
    taux_reduction numeric(3,2) NOT NULL,
    date_debut date NOT NULL,
    date_fin date NOT NULL,
    CONSTRAINT chk_dates_promotion CHECK ((date_debut <= date_fin)),
    CONSTRAINT promotion_taux_reduction_check CHECK (((taux_reduction > (0)::numeric) AND (taux_reduction <= (1)::numeric)))
);


ALTER TABLE public.promotion OWNER TO anonyme;

--
-- Name: promotion_id_promotion_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

ALTER TABLE public.promotion ALTER COLUMN id_promotion ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.promotion_id_promotion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: v_avis_approuves; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_avis_approuves AS
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
   FROM (((public.avis a
     JOIN public.produit p ON ((a.id_produit = p.id_produit)))
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
     JOIN public.client cl ON ((a.id_client = cl.id_client)))
  WHERE (a.modere = 'approuvé'::text)
  ORDER BY a.date_avis DESC;


ALTER VIEW public.v_avis_approuves OWNER TO anonyme;

--
-- Name: v_avis_details; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_avis_details AS
 SELECT a.id_avis,
    c.prenom_client,
    c.nom_client,
    p.nom_produit,
    a.note_etoiles,
    a.commentaire,
    a.date_avis,
    a.photo,
    a.modere
   FROM ((public.avis a
     JOIN public.client c ON ((a.id_client = c.id_client)))
     JOIN public.produit p ON ((a.id_produit = p.id_produit)));


ALTER VIEW public.v_avis_details OWNER TO anonyme;

--
-- Name: v_commandes_client; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_commandes_client AS
 SELECT cmd.id_commande,
    cmd.date_commande,
    cl.id_client,
    cl.nom_client,
    cl.prenom_client,
    cl.email_client,
    ((cmd.total_commande)::numeric)::text AS total_commande,
    cmd.statut_paiement,
    cmd.statut_commande,
    addr.ville,
    addr.pays
   FROM ((public.commande cmd
     JOIN public.client cl ON ((cmd.id_client = cl.id_client)))
     JOIN public.adresse addr ON ((cmd.id_adresse = addr.id_adresse)))
  ORDER BY cmd.date_commande DESC, cmd.id_commande DESC;


ALTER VIEW public.v_commandes_client OWNER TO anonyme;

--
-- Name: v_donnees_client; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_donnees_client AS
 SELECT DISTINCT ON (c.id_client) c.id_client,
    c.nom_client,
    c.prenom_client,
    c.email_client,
    a.id_adresse,
    a.rue,
    a.numero,
    a.code_postal,
    a.ville,
    a.pays
   FROM (public.client c
     LEFT JOIN public.adresse a ON ((c.id_client = a.id_client)))
  ORDER BY c.id_client, c.nom_client;


ALTER VIEW public.v_donnees_client OWNER TO anonyme;

--
-- Name: v_messages_contact; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_messages_contact AS
 SELECT mc.id_message,
    mc.id_client,
    mc.nom_contact,
    mc.email_contact,
    mc.num_commande,
    mc.sujet,
    mc.contenu,
    mc.photo,
    mc.date_message,
    mc.statut,
    cl.nom_client,
    cl.prenom_client,
    cmd.id_commande
   FROM ((public.message_contact mc
     LEFT JOIN public.client cl ON ((mc.id_client = cl.id_client)))
     LEFT JOIN public.commande cmd ON ((mc.num_commande = cmd.id_commande)))
  ORDER BY mc.date_message DESC;


ALTER VIEW public.v_messages_contact OWNER TO anonyme;

--
-- Name: v_produits_promotion; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.v_produits_promotion AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    (p.prix)::numeric AS prix_origine,
    (((p.prix)::numeric * ((1)::numeric - prom.taux_reduction)))::numeric(10,2) AS prix_reduit,
    round((prom.taux_reduction * (100)::numeric), 2) AS reduction_pourcent,
    prom.date_debut,
    prom.date_fin,
    p.stock,
    prom.id_promotion
   FROM ((public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
     JOIN public.promotion prom ON ((p.id_produit = prom.id_produit)))
  WHERE ((CURRENT_DATE >= prom.date_debut) AND (CURRENT_DATE <= prom.date_fin));


ALTER VIEW public.v_produits_promotion OWNER TO anonyme;

--
-- Name: produit_2 id_produit; Type: DEFAULT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.produit_2 ALTER COLUMN id_produit SET DEFAULT nextval('public.produit_2_id_produit_seq'::regclass);


--
-- Data for Name: admin; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.admin (id_admin, nom_admin, prenom_admin, email_admin, mot_de_passe) FROM stdin;
1	Admin	Test	admin@admin.com	$2y$12$WeDUB0NhiLSGREykjzfXDOQXu0iKcBYTcsxExolEMirkT0/gyNHAC
\.


--
-- Data for Name: adresse; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.adresse (id_adresse, id_client, rue, numero, code_postal, ville, pays) FROM stdin;
1	1	avenue Louise	128	1050	Bruxelles	Belgique
2	2	rue Saint-Gilles	47	4000	Liège	Belgique
3	3	rue de la République	23	69002	Lyon	France
4	4	Sint-Jansvliet	12	2000	Anvers	Belgique
5	5	rue de Rivoli	85	75001	Paris	France
6	6	cours Pasteur	34	33000	Bordeaux	France
\.


--
-- Data for Name: avis; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.avis (id_avis, id_client, id_produit, note_etoiles, commentaire, date_avis, modere, photo, id_commande) FROM stdin;
1	1	37	5	Mes BO Étoiles Filantes sont sublimes ! Légères, brillantes, je reçois plein de compliments quand je les porte. Coup de cœur total.	2026-06-02	approuvé	\N	1
2	2	43	5	Pendentif vraiment original, l'effet vitrail est superbe. La chaîne est de bonne qualité. Je recommande sans hésiter !	2026-05-20	approuvé	\N	3
3	2	49	4	Très jolies BO, légères et originales. Petit bémol sur la couleur du vert qui paraît un peu plus pâle qu'en photo, mais l'effet d'ensemble est top.	2026-05-20	approuvé	\N	3
4	3	40	5	Parure magnifique, le rendu est encore plus beau en vrai ! Cadeau pour ma sœur qui a adoré. Emballage soigné, livraison rapide.	2026-05-24	approuvé	\N	4
5	4	57	5	Le collier Storm est ma nouvelle pièce préférée. Pop, original et la qualité est au rendez-vous. Bravo Acrylia !	2026-06-15	approuvé	\N	6
6	4	58	5	Les BO assorties au collier Storm sont parfaites, l'ensemble fait vraiment son effet. Service client réactif et sympa.	2026-06-15	approuvé	\N	6
7	5	54	4	Parure Néon Glitter superbe, vraiment funky comme promis. Un peu de paillettes qui se sont détachées à la livraison mais rien de grave, le tout reste magnifique.	2026-06-10	approuvé	\N	7
\.


--
-- Data for Name: categorie; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.categorie (id_categorie, nom_categorie) FROM stdin;
2	Colliers
3	Bracelets
4	Parures
5	Accessoires
1	Boucles d'oreilles
\.


--
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.client (id_client, nom_client, prenom_client, email_client, mot_de_passe) FROM stdin;
1	Martin	Léa	lea.martin@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
2	Lefebvre	Hugo	hugo.lefebvre@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
3	Dubois	Camille	camille.dubois@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
4	Lambert	Noah	noah.lambert@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
5	Petit	Manon	manon.petit@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
6	Janssens	Théo	theo.janssens@test.com	$2y$12$mosComUWGVBu6Y9nPymejOt7Jx2gQBEq40t83DoO0R2CYW403wdxK
\.


--
-- Data for Name: commande; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.commande (id_commande, id_client, id_adresse, date_commande, total_commande, statut_paiement, statut_commande) FROM stdin;
1	1	1	2026-05-25 14:30:00	$34.00	t	envoyée
2	1	1	2026-06-26 10:15:00	$110.00	f	en_attente
3	2	2	2026-05-12 16:45:00	$70.00	t	envoyée
4	3	3	2026-05-15 11:20:00	$65.00	t	envoyée
5	3	3	2026-06-20 09:00:00	$38.00	t	approuvée
6	4	4	2026-06-08 19:30:00	$72.00	t	envoyée
7	5	5	2026-06-01 13:10:00	$68.00	t	envoyée
8	5	5	2026-06-25 18:00:00	$28.00	f	en_attente
9	6	6	2026-06-18 21:00:00	$64.00	t	approuvée
10	1	1	2026-06-05 12:00:00	$32.00	f	annulée
\.


--
-- Data for Name: commande_produit; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.commande_produit (id_commande, id_produit, quantite_commandee, prix_unitaire_achat) FROM stdin;
1	37	1	$34.00
2	44	1	$72.00
2	46	1	$38.00
3	43	1	$36.00
3	49	1	$34.00
4	40	1	$65.00
5	51	1	$38.00
6	57	1	$38.00
6	58	1	$34.00
7	54	1	$68.00
8	65	1	$28.00
9	64	2	$32.00
10	42	1	$32.00
\.


--
-- Data for Name: configuration; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.configuration (cle, valeur) FROM stdin;
accueil_banniere_lien	index_.php?page=produit.php
nom_nouvelle_collection	Collection du Coeur
accueil_banniere_bouton	Nouveauté
accueil_banniere_img	assets/images/hero_38d5e0b92f8dc9e4.png
accueil_apropos_bouton	En savoir plus
accueil_apropos_img	assets/images/apropos_ecef76866e85a364.png
accueil_apropos_texte	Bienvenue dans l'univers Acrylia, là où la géométrie devient funky ! Marque artisanale belge, nous façonnons à la main des bijoux aux formes bien calculées et aux couleurs vibrantes. Notre obsession ? Imaginer des pièces uniques et ultra-légères en plexiglas qui n'ont qu'un seul super-pouvoir : vous donner le sourire dès que vous les portez.\n\nBonne découverte !
\.


--
-- Data for Name: image_produit; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.image_produit (id_image, id_produit, url_image, ordre) FROM stdin;
43	37	assets/images/produits/prod_cb20afdfd92309e5.png	43
44	38	assets/images/produits/prod_d857247f61dec019.png	44
45	39	assets/images/produits/prod_aa0dba6cad10b01b.png	45
46	40	assets/images/produits/prod_73f332f7434fe9b5.png	46
47	41	assets/images/produits/prod_d2f3b979b53b4493.png	47
48	42	assets/images/produits/prod_45be1ef43fb8e388.png	48
49	43	assets/images/produits/prod_7e571c8103ac5a90.png	49
50	44	assets/images/produits/prod_78b4d783b0847380.png	50
51	45	assets/images/produits/prod_695510a58eab2ca0.png	51
52	46	assets/images/produits/prod_1a2171d4f4f32ee9.png	52
53	47	assets/images/produits/prod_6b5081b04a01cf47.png	53
54	48	assets/images/produits/prod_04ee9dee13e2e332.png	54
55	49	assets/images/produits/prod_739af74f342625f5.png	55
56	50	assets/images/produits/prod_72090ca92b3daac3.png	56
57	51	assets/images/produits/prod_a2aa53172460d0cb.png	57
58	52	assets/images/produits/prod_da09942ba1f412b7.png	58
59	53	assets/images/produits/prod_a56714c36e840b05.png	59
60	54	assets/images/produits/prod_3e4664d866ceb2ee.png	60
61	55	assets/images/produits/prod_d60b704c01304f37.png	61
62	56	assets/images/produits/prod_f102d9336cb6ea1a.png	62
63	57	assets/images/produits/prod_6d25cd83e277b55a.png	63
64	58	assets/images/produits/prod_8d8c33eecb182e18.png	64
65	59	assets/images/produits/prod_a2821c74564013cf.png	65
66	60	assets/images/produits/prod_645ffd358c7527f5.png	66
67	61	assets/images/produits/prod_a9dc04111af738ae.png	67
68	62	assets/images/produits/prod_b185773413d0c00a.png	68
69	63	assets/images/produits/prod_0ffdbe9205f641a6.png	69
70	64	assets/images/produits/prod_1cf904592636834a.png	70
71	65	assets/images/produits/prod_f33ee3ffc5ac8262.png	71
72	66	assets/images/produits/prod_b8cd988329f2b45f.png	72
\.


--
-- Data for Name: liste_envie; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.liste_envie (id_liste_envie, id_session, id_client, id_produit, date_ajout) FROM stdin;
1	d7e79058bb61438d800a3ebf468451a7	6	41	2026-06-27
\.


--
-- Data for Name: message_contact; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.message_contact (id_message, id_client, nom_contact, email_contact, num_commande, sujet, contenu, photo, date_message, statut, reponse) FROM stdin;
1	\N	Pauline Lemaire	pauline.lemaire@gmail.com	\N	Délais de livraison pour anniversaire	Bonjour, j'aimerais commander une parure pour un anniversaire le 5 juillet, est-ce que la livraison en France arrivera à temps si je passe ma commande aujourd'hui ? Merci pour votre réponse !	\N	2026-06-27 09:15:00	non_lu	\N
2	1	Léa Martin	lea.martin@test.com	1	Question entretien plexiglas	Bonjour, merci infiniment pour mes BO Étoiles Filantes, elles sont absolument magnifiques ! Petite question : comment nettoyer le plexiglas sans le rayer ? Merci d'avance.	\N	2026-06-05 11:30:00	lu	\N
3	3	Camille Dubois	camille.dubois@test.com	4	Petit souci sur ma parure	Bonjour, j'ai reçu la parure Étoiles Filantes hier, elle est magnifique. Petit souci : un crochet de boucle est un peu tordu à la réception. Est-ce qu'il est remplaçable ?	\N	2026-05-26 14:45:00	repondu	Bonjour Camille, bien sûr ! Envoyez-nous une photo des BO à bonjour@acrylia.shop et on vous renvoie un crochet de remplacement sous 48h. Belle journée à vous.
4	\N	Cécile Mathieu	cecile.m@yahoo.fr	\N	Pièce sur mesure possible ?	Bonjour, j'adore vraiment votre collection et j'aimerais savoir si vous faites du sur-mesure ? J'ai une idée précise de collier en tête pour la fête des mères. Merci !	\N	2026-06-22 17:00:00	non_lu	\N
5	2	Hugo Lefebvre	hugo.lefebvre@test.com	3	Juste un grand merci !	Juste un mot pour vous remercier, mes BO et mon collier Hexa Prisme sont splendides. Vraiment mon coup de cœur de l'année, continuez comme ça !	\N	2026-05-22 10:00:00	lu	\N
6	5	Manon Petit	manon.petit@test.com	\N	Matériaux et allergies nickel	Bonjour, je suis allergique au nickel. Est-ce que vos crochets et fermoirs en contiennent ? Je voudrais éviter une mauvaise surprise. Merci !	\N	2026-06-12 08:30:00	repondu	Bonjour Manon, nos crochets sont en acier inoxydable 316L, hypoallergéniques et sans nickel. Aucun souci pour vous ! Belle journée.
\.


--
-- Data for Name: panier; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.panier (id_panier, id_session, id_client) FROM stdin;
1	d7e79058bb61438d800a3ebf468451a7	6
2	e06b3ab3fc4153b100cd3fc83a008ddd	\N
3	9d7463687714769ba7f87f5418e334fa	\N
4	6828f38114aced52503718cfa964e537	\N
\.


--
-- Data for Name: panier_produit; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.panier_produit (id_panier, id_produit, quantite) FROM stdin;
4	37	2
\.


--
-- Data for Name: produit; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.produit (id_produit, id_categorie, nom_produit, description, prix, stock, en_vitrine, est_nouveau) FROM stdin;
52	2	Collier "Tutti Frutti" – Pendentif cœur transparent géométrique	Un pendentif cœur en plexiglas cristal dans lequel s'entremêlent une myriade de petites formes colorées : triangles fuchsia et verts, étoile blanche, demi-cercles bleus, points et virgules acidulés. Monté sur une chaîne dorée fine. L'effet vitrail pop dans un format romantique.	$36.00	9	t	t
40	4	Parure "Étoiles Filantes" – Collier + boucles d'oreilles	La parure complète : le collier pendentif étoile multi-couches sur chaîne argentée fine, accompagné des boucles d'oreilles assorties. Trois étoiles fuchsia, jaune et turquoise empilées, cœur pailleté. À porter en duo pour un look total pop.	$65.00	6	t	f
41	2	Collier "Mosaïque Pop" – Cascade géométrique	Un assemblage spectaculaire de neuf petites pièces de plexiglas : carrés fuchsia, triangles jaunes, vagues vertes, demi-cercles roses... Reliées par de petits anneaux dorés sur une chaîne ponctuée de disques martelés. Une pièce-déclaration.	$42.00	9	t	f
42	1	Boucles d'oreilles "Fleur Perlée" – Pétales translucides	Une fleur à quatre pétales en plexiglas translucide (rose, orange, jaune, bleu) avec un cœur doré pailleté serti d'une perle nacrée. Sous chaque fleur, une mini-fleur transparente garnie de confettis étoiles colorés. Romantique et joueur.	$32.00	14	t	f
43	2	Collier "Hexa Prisme" – Pendentif hexagonal	Hexagone en plexiglas cristal dans lequel sont emprisonnés trois formes colorées : un hexagone fuchsia, un grand triangle cyan, un petit triangle vert et un triangle jaune. Chaîne argentée fine et bélière polie. Un pendentif à effet vitrail moderne.	$36.00	11	t	f
39	1	Boucles d'oreilles "Confettis Néon" – Géométrie asymétrique	Mille morceaux de plexiglas teinté assemblés en cascade : carrés, ronds, éclairs et triangles dans des roses, bleus, verts et oranges fluorescents. Surmontées d'un disque doré martelé. Asymétriques, joyeuses, addictives.	$36.00	8	t	f
38	2	Collier "Pixel Drop" – Pendentif géométrique transparent	Pendentif en plexiglas transparent dans lequel se superposent un hexagone fuchsia, un grand triangle cyan et un triangle vert. Le tout monté sur une chaîne dorée fine. Effet « stained glass » contemporain.	$38.00	10	t	f
37	1	Boucles d'oreilles "Étoiles Filantes" – Arc-en-ciel pailleté	Boucles d'oreilles en plexiglas découpé : trois étoiles superposées (fuchsia, jaune et turquoise), avec un cœur central pailleté irisé. Crochets argentés. Légères et résolument funky, elles illuminent toutes les tenues.	$34.00	12	t	t
48	2	Collier "Fleur Perlée" – Court à breloques	Collier court en chaîne argentée ponctuée de petites perles de verre multicolores et de mini-fleurs transparentes pailletées. Au centre, une grande fleur à quatre pétales translucides (rose, orange, jaune, bleu) avec une perle nacrée sertie dans un cœur doré pailleté. Pièce délicate et romantique.	$40.00	9	t	f
49	1	Boucles d'oreilles "Hexa Prisme" – Hexagones transparents	Deux hexagones de plexiglas cristal renfermant chacun la même composition : un hexagone fuchsia, un grand triangle cyan, deux petits triangles vert et jaune. Crochets argentés. Légères, graphiques, et un effet vitrail très contemporain.	$34.00	12	t	t
51	1	Boucles d'oreilles "Funky Mix" – Lune & Vagues asymétriques	Une paire totalement asymétrique : à gauche, une vague rose, un triangle bleu, une lune jaune, un carré orange et un cercle vert. À droite, un croissant jaune, des nuages roses et turquoises, et un cercle rouge. Chaque oreille raconte une histoire différente.	$38.00	7	t	f
50	1	Boucles d'oreilles "Vibes Géométriques" – Longues triangles néon	Longues boucles d'oreilles : un disque fuchsia plein surmonte un empilement de demi-cercles orange et de grands triangles transparents (jaune néon, bleu, vert anis, violet). Une cascade graphique qui suit chaque mouvement.	$36.00	8	t	f
53	1	Boucles d'oreilles "Tutti Frutti" – Cœurs transparents géométriques	La version BO de la collection Tutti Frutti : deux cœurs en plexiglas cristal habités de petites formes géométriques multicolores (triangles, étoiles, points et virgules). Crochets dorés. À porter en duo avec le collier assorti pour le total look.	$32.00	10	t	t
54	4	Parure "Néon Glitter" – Cœur pailleté turquoise & rose néon	La parure star : un grand cœur rose néon bordé de pois blancs, traversé d'un cœur jaune fluo zébré de noir et surmonté d'un cœur central pailleté turquoise. Décliné en collier (perle violette pailletée et chaîne dorée) et en boucles d'oreilles puces assorties. Un duo flashy et joyeux qui ne passe pas inaperçu.	$68.00	5	t	t
55	2	Collier "Néon Glitter" – Pendentif cœur pailleté turquoise	Le pendentif vedette de la collection, à porter seul : un grand cœur rose néon bordé de pois et d'étoiles, cœur jaune fluo zébré, cœur turquoise pailleté au centre. Suspendu à une chaîne dorée par une perle violette pailletée. Pièce-déclaration qui illumine n'importe quel haut uni.	$42.00	8	t	t
56	1	Boucles d'oreilles "Néon Glitter" – Cœurs pailletés turquoise	Les mêmes cœurs flashy en version puces : rose néon bordé de pois, cœur jaune fluo zébré, cœur turquoise pailleté central. Petite puce violette pailletée en haut. Légères et ultra-pop, parfaites pour les cheveux relevés.	$32.00	12	t	t
57	2	Collier "Storm" – Pendentif éclair sur disque cobalt	Un grand disque cobalt en plexiglas traversé par un éclair vert anis cerclé de jaune fluo, surmontant un petit carré orange et une étoile fuchsia translucide. Chaîne argentée. Pour celles qui aiment l'énergie pop des années 80.	$38.00	10	t	t
58	1	Boucles d'oreilles "Storm" – Éclairs sur disques cobalt	Les boucles d'oreilles assorties au collier Storm : un disque cobalt frappé d'un éclair vert anis et jaune fluo, complété par un carré orange et une étoile fuchsia. Crochets dormeuses argentés. Légères et graphiques.	$34.00	11	t	t
59	2	Collier "Pop Plastron" – Cascade de disques, zigzags et étoiles	Un véritable plastron de bijoux : trois disques jaune translucide reliés par des zigzags fuchsia, et dessous, deux étoiles bleues entourées de losanges violets, triangles verts et un zigzag central qui dégringole. Sur chaîne argentée à billes. La pièce qui transforme un t-shirt blanc en tenue de soirée.	$48.00	7	t	t
60	1	Boucles d'oreilles "Pop Star" – Disque solaire et zigzag fuchsia	Un disque jaune translucide traversé d'un zigzag fuchsia, accompagné d'une étoile bleue, d'un losange violet et d'un triangle vert. Pendantes et asymétriques dans leur assemblage. À porter pour réveiller un look monochrome.	$36.00	9	t	t
61	4	Parure "Heart Sugar" – Cœur pailleté rose néon & swirl	La parure sucrée : un grand cœur rose néon pailleté, décoré d'un swirl orange et constellé de mini confettis bleus, oranges et roses. Surmonté d'un disque jaune fluo avec une étoile et une vague. Décliné en collier et boucles d'oreilles. Le côté Y2K assumé, version bonbon.	$65.00	4	t	t
62	1	Boucles d'oreilles "Heart Sugar" – Cœurs pailletés rose néon	Les boucles d'oreilles de la collection Heart Sugar : deux cœurs rose néon pailletés ornés d'un swirl orange et de mini confettis. Surmontés d'un disque jaune fluo. Ultra-girly, ultra-pop.	$34.00	13	t	t
46	1	Boucles d'oreilles "Marguerite Arc-en-ciel" – Longues pendantes	De longues boucles d'oreilles pendantes : anneau doré, grappe de perles colorées, puis une grande marguerite à dix pétales arc-en-ciel translucides avec un cœur blanc serti d'or pailleté. Légères et lumineuses.	$38.00	10	t	f
47	4	Parure "Pixel Drop" – Plexiglas transparent multicolore	Parure assortie autour du motif « Pixel Drop » : un pendentif sur chaîne dorée et une paire de boucles d'oreilles dans le même esprit. Hexagone fuchsia, triangles cyan et verts qui se superposent dans une plaque transparente. Effet vitrail garanti.	$65.00	6	t	f
44	4	Parure "Marguerite Arc-en-ciel" – Collier + boucles d'oreilles	La parure festive par excellence : grande marguerite arc-en-ciel à dix pétales translucides, cœur blanc serti d'un disque pailleté doré, déclinée en collier (chaîne dorée avec grappe de perles colorées) et en boucles d'oreilles longues assorties. Un duo qui ne passe pas inaperçu.	$72.00	5	t	f
45	4	Parure "Confettis Néon" – Cascade géométrique	Parure assortie : le collier en cascade géométrique multicolore (carrés, triangles, vagues, éclairs néon) sur chaîne dorée, et les boucles d'oreilles plumes de confettis correspondantes. Pour un total look pop-art assumé.	$70.00	7	t	f
63	1	Boucles d'oreilles "Wild Mix" – Pop asymétrique animal print	L'asymétrie poussée à son maximum : disque orange traversé d'un éclair jaune, cœur rose translucide, serpent turquoise, étoile violette pailletée, triangle vert anis et carré imprimé léopard noir et blanc. Chaque oreille est une petite œuvre. Pour celles qui n'ont jamais peur d'en faire trop.	$40.00	6	t	t
64	1	Boucles d'oreilles "Mirror Crush" – Cœurs miroir bicolores	Deux grands cœurs en plexiglas miroir : la bordure en rouge framboise réfléchissant, le cœur intérieur en bleu cobalt miroir. Un effet déco maximaliste très inspiration années 80. Crochets argentés. Légers malgré leur format XL.	$32.00	14	t	t
65	3	Bracelet "Funky Groovy" – Perles, smileys et charms cassette	Un bracelet débordant de bonne humeur : perles colorées en heishi, smileys jaunes, cassette audio rose, éclair turquoise pailleté, étoile filante, cœur rose, et le mot "FUNKY GROOVY" en perles alphabet multicolores. Élastique extensible, taille unique. À empiler ou à porter seul.	$28.00	12	t	t
66	3	Set bracelets "Y2K Stack" – 4 bracelets multi-rangs à empiler	Un set complet pour les addicts du layering : 4 bracelets à empiler — un rang heishi turquoise avec breloque cœur dorée et éclairs, un rang heishi fuchsia avec smiley jaune, un rang multicolore avec étoile orange et symbole peace, et un cordon tressé rose avec cœur fuchsia. Fermoirs ajustables.	$45.00	8	t	t
\.


--
-- Data for Name: produit_2; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.produit_2 (id_produit, id_categorie, nom_produit, description, prix, stock, en_vitrine, est_nouveau) FROM stdin;
4	5	Broche "Doudou 2024"	Célébrez la Ducasse de Mons avec style. Cette broche en plexiglas bicouche joue sur les contrastes entre un cadre vert émeraude et un centre rouge vif gravé.	$1,500.00	20	t	f
8	1	Boucles d'oreilles "Cœur Sacré" – Imprimé Léopard & Rouge	nspirées par l'iconographie traditionnelle du Cœur Sacré, ces boucles d'oreilles uniques combinent un style rétro avec une touche moderne. Elles présentent une plaque en acrylique avec un imprimé léopard sauvage, ornée d'un motif cœur rouge vif avec des rayons dorés gravés, symbolisant l'amour passionné et l'énergie solaire. Le design est complété par des anneaux articulés de couleur dorée pour un port confortable et sécurisé.\n\nDétails du produit :\n\nDesign : Pendentif cœur avec imprimé léopard et motif "soleil" rayonnant.\n\nCouleurs : Or, Rouge, Imprimé Léopard (Marron/Noir).\n\nMatériau : Acrylique et alliage métallique de haute qualité.\n\nType de fermoir : Créoles articulées (type 'huggies' ou dormeuses)\n\nOccasion :\nIdéal pour ajouter une touche de glamour audacieux à une tenue décontractée ou pour compléter un look de soirée chic. C'est également un cadeau original et symbolique pour un être cher.	$2,900.00	15	t	t
33	1	Boucles d'oreilles "Éclat Solaire" – Jaune Lime & Argent	Clous triangulaires argentés supportant de grands triangles jaune lime néon avec motif solaire noir. Une version audacieuse pour un look affirmé.	$3,200.00	14	t	f
7	1	Boucles d'oreilles "Cœur Sacré" – Rouge Radiant & Or	Affichez votre passion avec éclat !\n\nInspirées de l'iconographie traditionnelle des ex-voto, ces boucles d'oreilles allient mysticisme vintage et design contemporain. Ce modèle "Statement" joue sur les contrastes pour illuminer votre visage.\n\nLe Design : Un cœur en acrylique rouge translucide qui capture la lumière, surmonté d'un médaillon "Soleil" finement ciselé au fini doré brillant.\n\nLes Matériaux : Fabriquées en acrylique de haute qualité et en métal doré (acier inoxydable ou laiton), garantissant une grande légèreté pour un confort optimal tout au long de la journée.\n\nLe Style : Parfaites pour rehausser une tenue sobre ou pour compléter un look bohème-chic.\n\nPoints forts :\n\nEffet "vitrail" grâce à la transparence de l'acrylique.\n\nUltra-légères : ne tirent pas sur le lobe.\n\nFermoir type créoles (huggies) pour une sécurité maximale.	$2,900.00	24	t	t
34	1	Boucles d'oreilles "Éclat Solaire" – Rose Néon & Bronze	Puces triangulaires bronzes avec grands pendentifs rose néon et rayons dorés. Parfait pour dynamiser une tenue sobre.	$3,200.00	16	t	f
36	1	Boucles d''oreilles "Cœurs Superposés" – Lime & Lagon	Design original associant deux cœurs superposés en acrylique jaune lime et vert lagon, ornés du motif rayonnant signature sur créoles dorées.	$3,400.00	12	t	t
35	1	Boucles d''oreilles "Éclat Solaire" – Noir & Or Classique'	L'alliance parfaite du chic et du graphique : base noire opaque et rayons solaires dorés montés sur clous triangulaires.	$3,200.00	25	t	f
2	1	Boucles d'oreilles delta-chrome	Bijoux d'oreilles au design architectural avec finition bi-texture (mate et brillante). Incorpore une fine chaîne pendante pour un look néo-industriel. Matériau : Alliage haute résistance.	$2,800.00	0	t	f
9	1	Boucles d'oreilles "Éclat Géométrique" – Marbré Pourpre & Or Miroir	L'audace du design à l'état pur.\n\nLaissez-vous séduire par ces boucles d'oreilles au style avant-garde. Ce modèle unique joue sur l'assemblage de formes triangulaires pour créer une silhouette dynamique, évoquant à la fois l'origami et des ailes abstraites.\n\nLe Design : Une composition asymétrique et graphique qui structure le visage. Le contraste entre les pointes acérées et les courbes des motifs marbrés crée une pièce de caractère, résolument moderne.\n\nLes Matériaux : Un mélange sophistiqué d'acrylique marbré (mélange de pourpre, noir et jaune acide) et d'acrylique miroir doré pour un éclat incomparable.\n\nLe Style : Une pièce "Arty" parfaite pour celles qui aiment les bijoux audacieux qui ne ressemblent à aucun autre.\n\nPoints forts :\n\nTexture unique : Chaque pièce marbrée est différente, rendant vos boucles d'oreilles uniques.\n\nFini miroir : Les sections dorées réfléchissent la lumière comme de véritables miroirs.\n\nConfort : Malgré leur aspect imposant, l'acrylique permet de garder un poids plume pour un port prolongé sans fatigue.\n\nCatégorie : Boucles d'oreilles (Pendantes / Graphiques)\n\nCouleurs : Or Miroir, Pourpre, Jaune Lime et Noir\n\nMatériaux : Acrylique (Plexiglas) de haute qualité	$3,900.00	20	t	f
32	1	Boucles d'oreilles "Cœur Solaire" – Vert Émeraude	Créoles avec pendentifs cœurs en acrylique vert émeraude translucide et motif solaire doré. Un mélange de fraîcheur organique et de raffinement précieux.	$2,900.00	18	t	t
3	2	Pendentif Delta-Chrome avec chaîne	Collier avec pendentif architectural assorti aux boucles d'oreilles Delta-Chrome. Finition bicolore (mate et brillante) et détail de chaînette pendante. Livré avec une chaîne fine en métal argenté de 45 cm.	$2,750.00	45	t	f
1	3	Bracelet Perles	Bracelet artisanal fait avec des perles naturelles	$2,500.00	7	t	f
12	1	Boucles d'oreilles "Prisme Stellaire" – Noir Mat & Éclats de Galaxie	Plongez dans l'infini avec élégance.\n\nCes boucles d'oreilles arborent un design géométrique audacieux, inspiré par la profondeur des nuits étoilées. Ce modèle joue sur le contraste saisissant entre une structure noire rigoureuse et un cœur scintillant aux reflets changeants.\n\nLe Design : Une forme en double diamant (losange) aux lignes tranchantes et modernes. La découpe centrale laisse apparaître une texture riche, créant un effet de fenêtre sur une lointaine galaxie.\n\nLes Matériaux : Fabriquées avec précision en acrylique noir mat pour un fini sobre et contemporain. Le centre est composé d'un acrylique pailleté multicouleur (glitter) intégrant des éclats vert émeraude, argentés et pourpres.\n\nLe Style : À la fois mystérieux et chic, c'est l'accessoire idéal pour celles qui cherchent une pièce "Dark Chic" ou un bijou qui se marie aussi bien avec un look minimaliste qu'une tenue de soirée.\n\nPoints forts :\n\nContraste de matières : L'opposition entre le noir mat et le brillant des paillettes attire irrésistiblement le regard.\n\nUltra-Légères : Conçues pour être portées toute la journée sans aucune gêne.\n\nÉclat hypnotique : Selon l'angle de la lumière, les paillettes révèlent différentes nuances de bleu, de vert et de violet.\n\nCaractéristiques techniques\nCatégorie : Boucles d'oreilles (Pendantes Géométriques)\n\nCouleurs : Noir, Vert d'eau, Argent et Pourpre\n\nMatériaux : Acrylique (Plexiglas) de haute qualité	$3,600.00	0	t	f
13	2	Collier "Cœur Sacré" – Rouge Radiant & Or	Inspiré par l'iconographie traditionnelle du Cœur Sacré, ce collier audacieux est une pièce maîtresse vibrante et captivante. Il présente un grand pendentif cœur en acrylique rouge translucide, découpé au laser avec précision, orné d'un motif "Cœur Sacré" rayonnant en laiton doré poli.\n\nDétails du produit :\n\nDesign : Grand pendentif cœur avec motif "soleil" rayonnant et flamme dorés.\n\nMatériaux : Acrylique rouge translucide et laiton doré poli.\n\nChaîne : Fine chaîne en laiton doré poli, avec des perles d'acrylique rouge translucide et des perles de métal doré disposées de manière symétrique de chaque côté du pendentif.\n\nFermoir : Fermoir homard délicat en laiton doré poli.	$4,500.00	6	f	t
11	1	Boucles d'oreilles "Confetti multidimensionnel"	L'élégance graphique rencontre l'éclat de la fête.\n\nLaissez-vous séduire par ce modèle aux lignes architecturales et au fini scintillant. Ces boucles d'oreilles jouent sur un contraste fort entre la sobriété du noir et l'énergie d'un motif "confetti" multidimensionnel.\n\nLe Design : Une composition géométrique en deux parties. Une pièce supérieure allongée noire qui vient structurer le lobe, et une pièce inférieure hexagonale parée de reflets violets et fuchsia.\n\nLes Matériaux : Un assemblage d'acrylique noir opaque et d'acrylique pailleté (glitter) de haute qualité. Le mélange de paillettes violettes, mauves et magenta crée un effet de profondeur unique à chaque paire.\n\nLe Style : Un look "Art Déco Moderne" qui apporte une touche de sophistication à une tenue de soirée ou réveille un look de bureau minimaliste.\n\nPoints forts :\n\nÉclat multidimensionnel : Les paillettes capturent la lumière à chaque mouvement.\n\nStructure unique : Un design angulaire qui souligne les traits du visage.\n\nPlume : Comme pour toute notre gamme en acrylique, elles sont extrêmement légères malgré leur volume.	$3,200.00	2	f	f
27	2	Collier "Éclat Solaire" – Rose Néon & Or	Pendentif triangulaire en acrylique rose néon orné d'un motif solaire rayonnant en laiton doré. Monté sur une fine chaîne noire pour un contraste moderne.	$3,800.00	12	t	f
26	1	Boucles d'oreilles "X-Opaline"	Design d'avant-garde présentant une structure en "X" doré sur un fond irisé aux reflets changeants. La partie inférieure est en acrylique marbré vert d'eau pour un rendu organique et moderne.	$3,900.00	7	t	f
23	1	Boucles d'oreilles "Obsidienne & Or"	Boucles d'oreilles pendantes extra-longues au design asymétrique. Mariage élégant d'acrylique noir opaque et de miroirs dorés brossés pour une allure sculpturale et luxueuse.	$3,800.00	8	t	f
31	1	Boucles d'oreilles "Cœur Solaire" – Or Miroir	Créoles dorées supportant un cœur en acrylique miroir or avec découpe centrale en forme de cœur et rayons solaires gravés. Captent intensément la lumière.	$2,900.00	20	t	t
22	1	Boucles d''oreilles "Prisme Améthyste"	Design audacieux composé d'un triangle supérieur en acrylique noir pailleté et d'un triangle inférieur violet translucide gravé. Agrémentées d'une fine chaîne pendante en métal argenté.	$3,500.00	12	t	f
25	1	Boucles d'oreilles "Galaxie Hexagonale"	Pièces imposantes en forme d'hexagone transparent incrusté d'éclats bleus et irisés façon galaxie. Une barre centrale noire vient structurer ce design cosmique et lumineux.	$4,200.00	5	t	f
20	1	Créoles "Vortex Néon	Boucles d'oreilles créoles dorées arborant un design géométrique superposé en acrylique violet miroir et orange fluo. Un contraste vibrant pour un look résolument pop et architectural.	$3,200.00	15	t	f
21	1	Créoles "Gothic Cross" Argentées	Créoles élégantes avec pendentifs en forme de croix au fini argenté et détails noirs. Une longue chaîne fine pend de chaque croix pour un style gothique-chic sophistiqué.	$2,900.00	10	t	f
29	2	Collier "Prisme Stellaire" – Double Triangle	Pendentif articulé composé d'un triangle supérieur noir pailleté galaxie et d'un triangle inférieur violet. Une pièce architecturale assortie aux boucles d'oreilles de la même gamme.	$4,200.00	8	t	f
5	1	Boucles d’oreilles "Éclat Solaire" – Miroir Bleu & Or	Illuminez votre style avec ces boucles d’oreilles architecturales au design audacieux. Alliant la modernité des formes géométriques et la chaleur d'un motif solaire, ces bijoux captent et reflètent la lumière à chaque mouvement.\n\nDesign unique : Une structure en triangle inversé avec un fini miroir bleu ciel, surmontée d'un soleil rayonnant doré.\n\nMatériaux : Acrylique de haute qualité (effet miroir et fini mat doré), garantissant une grande légèreté et un confort optimal pour vos oreilles toute la journée.\n\nStyle : Une pièce forte (statement piece) idéale pour rehausser une tenue sobre ou pour briller lors d'une occasion spéciale.\n\nFermeture : Tige en acier inoxydable (hypoallergénique).	$2,800.00	15	t	f
6	1	Boucles d’oreilles "Kaleido Chic"	Osez l'originalité avec ces boucles d'oreilles audacieuses, véritable clin d'œil au design des années 80 et au courant Memphis. Ce modèle joue sur le contraste des matières pour un look artistique et dynamique.\n\nJeu de textures : Un assemblage complexe de fini argenté brossé, d'acrylique miroir rose vibrant et d'un triangle "confetti" multicolore aux reflets scintillants.\n\nDesign graphique : Une forme allongée et asymétrique qui structure le visage et apporte une touche résolument moderne.\n\nConfort : Malgré leur aspect imposant, ces boucles restent très légères grâce à l'utilisation de l'acrylique, évitant toute fatigue du lobe.\n\nL'idée style : Parfaites pour réveiller un look monochrome (noir, blanc ou gris) ou pour compléter une tenue festive et colorée.	$3,500.00	5	t	f
28	2	Collier "Éclat Solaire" – Jaune Lime & Noir	Variante graphique du pendentif triangulaire en acrylique jaune lime translucide avec motif solaire noir opaque. Style industriel et minimaliste.	$3,500.00	10	t	f
19	1	Boucles d''oreilles "LOVE" Miroir	Petites boucles d'oreilles en forme de cœur avec un fini miroir argenté. Gravées avec les lettres "LO" et "VE" et agrémentées d'une fine chaînette pendante pour une touche romantique et moderne.	$2,400.00	20	t	t
30	2	Collier "Cœur Sacré" – Noir Ébène & Or	Pendentif en forme de cœur composé d'une base dorée et d'un insert noir profond, gravé d'un motif rayonnant. L'élégance intemporelle alliée au symbolisme vintage.	$4,500.00	15	t	t
10	1	Boucles d'oreilles "Cœur Sacré" – Rose Électrique & Or	Osez le romantisme version pop !\n\nInspirées des célèbres ex-voto, ces boucles d'oreilles revisitent le symbole du Cœur Sacré dans une teinte rose néon ultra-vitaminée. C’est la pièce parfaite pour celles qui cherchent à allier spiritualité vintage et modernité audacieuse.\n\nLe Design : Un cœur généreux en acrylique rose translucide qui s'illumine au moindre rayon de soleil. Il est orné d'un médaillon central doré aux rayons finement ciselés, apportant une touche précieuse et rayonnante.\n\nLes Matériaux : Conçues en acrylique haute qualité, elles offrent une brillance exceptionnelle tout en restant d'une légèreté absolue. Les anneaux dorés ajoutent une finition élégante et robuste.\n\nLe Style : Un accessoire "Candy-Chic" qui apporte instantanément du peps à un look urbain ou une robe de soirée.\n\nPoints forts :\n\nCouleur vibrante : Un rose "Neon Pink" translucide qui ne passe pas inaperçu.\n\nConfort total : Tellement légères que vous oublierez que vous les portez.\n\nFermoir sécurisé : Créoles articulées en métal doré pour une mise en place facile.\n\nCaractéristiques techniques\nCatégorie : Boucles d'oreilles\n\nCouleurs : Rose Vif Translucide / Or\n\nMatériaux : Acrylique (Plexiglas) et alliage métallique doré\n\nType de fermoir : Créoles (Huggies)\n\nDimensions : Hauteur totale d'environ 4 cm	$3,099.00	15	t	t
\.


--
-- Data for Name: promotion; Type: TABLE DATA; Schema: public; Owner: anonyme
--

COPY public.promotion (id_promotion, id_produit, taux_reduction, date_debut, date_fin) FROM stdin;
1	46	0.20	2026-06-26	2026-12-26
2	38	0.20	2026-06-26	2026-12-26
3	40	0.30	2026-06-26	2026-12-26
4	41	0.20	2026-06-26	2026-12-26
5	43	0.15	2026-06-26	2026-12-26
6	64	0.15	2026-06-26	2026-12-26
\.


--
-- Name: admin_id_admin_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.admin_id_admin_seq', 1, true);


--
-- Name: adresse_id_adresse_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.adresse_id_adresse_seq', 6, true);


--
-- Name: avis_id_avis_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.avis_id_avis_seq', 7, true);


--
-- Name: categorie_id_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.categorie_id_seq', 31, true);


--
-- Name: client_id_client_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.client_id_client_seq', 6, true);


--
-- Name: commande_id_commande_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.commande_id_commande_seq', 10, true);


--
-- Name: image_produit_id_image_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.image_produit_id_image_seq', 57, true);


--
-- Name: liste_envie_id_liste_envie_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.liste_envie_id_liste_envie_seq', 1, true);


--
-- Name: message_contact_id_message_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.message_contact_id_message_seq', 6, true);


--
-- Name: panier_id_panier_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.panier_id_panier_seq', 4, true);


--
-- Name: produit_2_id_produit_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.produit_2_id_produit_seq', 1, false);


--
-- Name: produit_id_produit_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.produit_id_produit_seq', 66, true);


--
-- Name: promotion_id_promotion_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.promotion_id_promotion_seq', 6, true);


--
-- Name: admin admin_email_admin_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_email_admin_key UNIQUE (email_admin);


--
-- Name: admin admin_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_pkey PRIMARY KEY (id_admin);


--
-- Name: adresse adresse_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.adresse
    ADD CONSTRAINT adresse_pkey PRIMARY KEY (id_adresse);


--
-- Name: avis avis_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT avis_pkey PRIMARY KEY (id_avis);


--
-- Name: categorie categorie_nom_categorie_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_nom_categorie_key UNIQUE (nom_categorie);


--
-- Name: categorie categorie_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_pkey PRIMARY KEY (id_categorie);


--
-- Name: client client_email_client_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_email_client_key UNIQUE (email_client);


--
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id_client);


--
-- Name: commande commande_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_pkey PRIMARY KEY (id_commande);


--
-- Name: commande_produit commande_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT commande_produit_pkey PRIMARY KEY (id_commande, id_produit);


--
-- Name: configuration configuration_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.configuration
    ADD CONSTRAINT configuration_pkey PRIMARY KEY (cle);


--
-- Name: image_produit image_produit_ordre_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_ordre_key UNIQUE (ordre);


--
-- Name: image_produit image_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_pkey PRIMARY KEY (id_image);


--
-- Name: image_produit image_produit_url_image_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_url_image_key UNIQUE (url_image);


--
-- Name: liste_envie liste_envie_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT liste_envie_pkey PRIMARY KEY (id_liste_envie);


--
-- Name: message_contact message_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT message_contact_pkey PRIMARY KEY (id_message);


--
-- Name: panier panier_id_session_key; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_session_key UNIQUE (id_session);


--
-- Name: panier panier_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_pkey PRIMARY KEY (id_panier);


--
-- Name: panier_produit panier_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT panier_produit_pkey PRIMARY KEY (id_panier, id_produit);


--
-- Name: produit produit_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_pkey PRIMARY KEY (id_produit);


--
-- Name: promotion promotion_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT promotion_pkey PRIMARY KEY (id_promotion);


--
-- Name: produit uq_nom_produit; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT uq_nom_produit UNIQUE (nom_produit);


--
-- Name: unique_avis_commande_produit; Type: INDEX; Schema: public; Owner: anonyme
--

CREATE UNIQUE INDEX unique_avis_commande_produit ON public.avis USING btree (id_commande, id_produit) WHERE (id_commande IS NOT NULL);


--
-- Name: adresse fk_adresse_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.adresse
    ADD CONSTRAINT fk_adresse_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- Name: avis fk_avis_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT fk_avis_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- Name: avis fk_avis_commande; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT fk_avis_commande FOREIGN KEY (id_commande) REFERENCES public.commande(id_commande) ON DELETE SET NULL;


--
-- Name: avis fk_avis_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT fk_avis_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- Name: commande fk_commande_adresse; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT fk_commande_adresse FOREIGN KEY (id_adresse) REFERENCES public.adresse(id_adresse) ON DELETE RESTRICT;


--
-- Name: commande fk_commande_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT fk_commande_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE RESTRICT;


--
-- Name: commande_produit fk_commande_produit_commande; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT fk_commande_produit_commande FOREIGN KEY (id_commande) REFERENCES public.commande(id_commande) ON DELETE CASCADE;


--
-- Name: commande_produit fk_commande_produit_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT fk_commande_produit_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE RESTRICT;


--
-- Name: image_produit fk_image_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT fk_image_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- Name: liste_envie fk_liste_envie_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT fk_liste_envie_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- Name: liste_envie fk_liste_envie_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT fk_liste_envie_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- Name: message_contact fk_message_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT fk_message_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE SET NULL;


--
-- Name: message_contact fk_message_commande; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT fk_message_commande FOREIGN KEY (num_commande) REFERENCES public.commande(id_commande) ON DELETE SET NULL;


--
-- Name: panier fk_panier_client; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT fk_panier_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE SET NULL;


--
-- Name: panier_produit fk_panier_produit_panier; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT fk_panier_produit_panier FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier) ON DELETE CASCADE;


--
-- Name: panier_produit fk_panier_produit_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT fk_panier_produit_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- Name: produit fk_produit_categorie; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT fk_produit_categorie FOREIGN KEY (id_categorie) REFERENCES public.categorie(id_categorie) ON DELETE RESTRICT;


--
-- Name: promotion fk_promotion_produit; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT fk_promotion_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: anonyme
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;


--
-- PostgreSQL database dump complete
--

\unrestrict bygsRL1wCwTGVPvgxXzbcn0eXId9p6Rz6imj4TgAoN3z8PJR6eAUnpSe3JBao0s

