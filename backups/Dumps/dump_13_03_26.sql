--
-- PostgreSQL database dump
--

\restrict YeAQMVScb7pChQLP4V9f304XRoDHfJ6dMumBoMVmO4FA6utAD4vOqMFgBt8JHA0

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-03-13 11:33:21

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 33751)
-- Name: admin; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admin (
    id_admin integer NOT NULL,
    nom_admin text NOT NULL,
    prenom_admin text NOT NULL,
    email_admin text NOT NULL,
    mot_de_passe text NOT NULL
);


--
-- TOC entry 221 (class 1259 OID 33783)
-- Name: adresse; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 231 (class 1259 OID 33988)
-- Name: avis; Type: TABLE; Schema: public; Owner: -
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
    CONSTRAINT avis_modere_check CHECK ((modere = ANY (ARRAY['en_attente'::text, 'approuvé'::text, 'refusé'::text]))),
    CONSTRAINT avis_note_etoiles_check CHECK (((note_etoiles >= 1) AND (note_etoiles <= 5)))
);


--
-- TOC entry 222 (class 1259 OID 33805)
-- Name: categorie; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categorie (
    id_categorie integer NOT NULL,
    nom_categorie text NOT NULL
);


--
-- TOC entry 220 (class 1259 OID 33767)
-- Name: client; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.client (
    id_client integer NOT NULL,
    nom_client text NOT NULL,
    prenom_client text NOT NULL,
    email_client text NOT NULL,
    mot_de_passe text NOT NULL
);


--
-- TOC entry 229 (class 1259 OID 33941)
-- Name: commande; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.commande (
    id_commande integer NOT NULL,
    id_client integer NOT NULL,
    id_adresse integer NOT NULL,
    date_commande date NOT NULL,
    total_commande money NOT NULL,
    statut_paiement boolean NOT NULL,
    statut_commande text DEFAULT 'en_attente'::text NOT NULL,
    CONSTRAINT commande_statut_commande_check CHECK ((statut_commande = ANY (ARRAY['en_attente'::text, 'approuvée'::text, 'annulée'::text, 'envoyée'::text])))
);


--
-- TOC entry 230 (class 1259 OID 33968)
-- Name: commande_produit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.commande_produit (
    id_commande integer NOT NULL,
    id_produit integer NOT NULL,
    quantite_commandee integer NOT NULL,
    prix_unitaire_achat money NOT NULL
);


--
-- TOC entry 224 (class 1259 OID 33840)
-- Name: image_produit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.image_produit (
    id_image integer NOT NULL,
    id_produit integer NOT NULL,
    url_image text NOT NULL,
    ordre integer NOT NULL
);


--
-- TOC entry 228 (class 1259 OID 33918)
-- Name: liste_envie; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.liste_envie (
    id_liste_envie integer NOT NULL,
    id_session text NOT NULL,
    id_client integer,
    id_produit integer NOT NULL,
    date_ajout date NOT NULL
);


--
-- TOC entry 232 (class 1259 OID 34019)
-- Name: message_contact; Type: TABLE; Schema: public; Owner: -
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
    date_message date NOT NULL,
    lu boolean NOT NULL
);


--
-- TOC entry 226 (class 1259 OID 33881)
-- Name: panier; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.panier (
    id_panier integer NOT NULL,
    id_session text NOT NULL,
    id_client integer
);


--
-- TOC entry 227 (class 1259 OID 33898)
-- Name: panier_produit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.panier_produit (
    id_panier integer NOT NULL,
    id_produit integer NOT NULL,
    quantite integer NOT NULL,
    CONSTRAINT panier_produit_quantite_check CHECK ((quantite >= 1))
);


--
-- TOC entry 223 (class 1259 OID 33818)
-- Name: produit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.produit (
    id_produit integer NOT NULL,
    id_categorie integer NOT NULL,
    nom_produit text NOT NULL,
    description text,
    prix money NOT NULL,
    stock integer NOT NULL,
    CONSTRAINT produit_stock_check CHECK ((stock >= 0))
);


--
-- TOC entry 225 (class 1259 OID 33862)
-- Name: promotion; Type: TABLE; Schema: public; Owner: -
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


--
-- TOC entry 237 (class 1259 OID 34063)
-- Name: v_avis_approuves; Type: VIEW; Schema: public; Owner: -
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


--
-- TOC entry 238 (class 1259 OID 34068)
-- Name: v_avis_moyennes_produits; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_avis_moyennes_produits AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    round(avg(a.note_etoiles), 2) AS note_moyenne,
    count(a.id_avis) AS nombre_avis
   FROM ((public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
     LEFT JOIN public.avis a ON (((p.id_produit = a.id_produit) AND (a.modere = 'approuvé'::text))))
  GROUP BY p.id_produit, p.nom_produit, c.nom_categorie
  ORDER BY (round(avg(a.note_etoiles), 2)) DESC NULLS LAST;


--
-- TOC entry 236 (class 1259 OID 34058)
-- Name: v_commandes_client; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_commandes_client AS
 SELECT cmd.id_commande,
    cmd.date_commande,
    cl.id_client,
    cl.nom_client,
    cl.prenom_client,
    cl.email_client,
    cmd.total_commande,
    cmd.statut_paiement,
    cmd.statut_commande,
    addr.ville,
    addr.pays
   FROM ((public.commande cmd
     JOIN public.client cl ON ((cmd.id_client = cl.id_client)))
     JOIN public.adresse addr ON ((cmd.id_adresse = addr.id_adresse)))
  ORDER BY cmd.date_commande DESC;


--
-- TOC entry 241 (class 1259 OID 34087)
-- Name: v_messages_contact_non_lus; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_messages_contact_non_lus AS
 SELECT mc.id_message,
    mc.nom_contact,
    mc.email_contact,
    mc.sujet,
    mc.date_message,
    cl.nom_client,
    cl.prenom_client,
    cmd.id_commande
   FROM ((public.message_contact mc
     LEFT JOIN public.client cl ON ((mc.id_client = cl.id_client)))
     LEFT JOIN public.commande cmd ON ((mc.num_commande = cmd.id_commande)))
  WHERE (mc.lu = false)
  ORDER BY mc.date_message DESC;


--
-- TOC entry 239 (class 1259 OID 34073)
-- Name: v_panier_detail; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_panier_detail AS
 SELECT p.id_panier,
    p.id_session,
    p.id_client,
    pr.id_produit,
    pr.nom_produit,
    pp.quantite,
    pr.prix,
    (((pr.prix)::numeric * (pp.quantite)::numeric))::money AS sous_total
   FROM ((public.panier p
     JOIN public.panier_produit pp ON ((p.id_panier = pp.id_panier)))
     JOIN public.produit pr ON ((pp.id_produit = pr.id_produit)))
  ORDER BY p.id_panier, pr.nom_produit;


--
-- TOC entry 234 (class 1259 OID 34048)
-- Name: v_produits_categorie; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_produits_categorie AS
 SELECT p.id_produit,
    p.nom_produit,
    c.id_categorie,
    c.nom_categorie,
    p.prix,
    p.stock,
    p.description,
    img.url_image,
    img.ordre
   FROM ((public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
     LEFT JOIN public.image_produit img ON ((p.id_produit = img.id_produit)))
  ORDER BY p.id_produit, img.ordre;


--
-- TOC entry 233 (class 1259 OID 34044)
-- Name: v_produits_disponibles; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_produits_disponibles AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    p.stock,
    p.description
   FROM (public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
  WHERE (p.stock > 0)
  ORDER BY c.nom_categorie, p.nom_produit;


--
-- TOC entry 240 (class 1259 OID 34078)
-- Name: v_produits_hors_stock; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_produits_hors_stock AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    p.stock
   FROM (public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
  WHERE (p.stock = 0)
  ORDER BY c.nom_categorie, p.nom_produit;


--
-- TOC entry 235 (class 1259 OID 34053)
-- Name: v_produits_promotion; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_produits_promotion AS
 SELECT p.id_produit,
    p.nom_produit,
    c.nom_categorie,
    p.prix,
    (((p.prix)::numeric * ((1)::numeric - prom.taux_reduction)))::money AS prix_reduit,
    round((prom.taux_reduction * (100)::numeric), 2) AS reduction_pourcent,
    prom.date_debut,
    prom.date_fin,
    p.stock
   FROM ((public.produit p
     JOIN public.categorie c ON ((p.id_categorie = c.id_categorie)))
     JOIN public.promotion prom ON ((p.id_produit = prom.id_produit)))
  WHERE ((CURRENT_DATE >= prom.date_debut) AND (CURRENT_DATE <= prom.date_fin))
  ORDER BY (round((prom.taux_reduction * (100)::numeric), 2)) DESC;


--
-- TOC entry 5170 (class 0 OID 33751)
-- Dependencies: 219
-- Data for Name: admin; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.admin (id_admin, nom_admin, prenom_admin, email_admin, mot_de_passe) VALUES (1, 'Emeline', 'Caulier', 'emeline96@hotmail.com', '1234');


--
-- TOC entry 5172 (class 0 OID 33783)
-- Dependencies: 221
-- Data for Name: adresse; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.adresse (id_adresse, id_client, rue, numero, code_postal, ville, pays) VALUES (1, 1, 'Rue de la Paix', '42', '75000', 'Paris', 'France');


--
-- TOC entry 5182 (class 0 OID 33988)
-- Dependencies: 231
-- Data for Name: avis; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5173 (class 0 OID 33805)
-- Dependencies: 222
-- Data for Name: categorie; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.categorie (id_categorie, nom_categorie) VALUES (1, 'Boucles d''oreilles');
INSERT INTO public.categorie (id_categorie, nom_categorie) VALUES (2, 'Colliers');
INSERT INTO public.categorie (id_categorie, nom_categorie) VALUES (3, 'Bracelets');
INSERT INTO public.categorie (id_categorie, nom_categorie) VALUES (4, 'Parures');
INSERT INTO public.categorie (id_categorie, nom_categorie) VALUES (5, 'Accessoires');


--
-- TOC entry 5171 (class 0 OID 33767)
-- Dependencies: 220
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.client (id_client, nom_client, prenom_client, email_client, mot_de_passe) VALUES (1, 'Dupont', 'Marie', 'marie.dupont@email.com', '1111');


--
-- TOC entry 5180 (class 0 OID 33941)
-- Dependencies: 229
-- Data for Name: commande; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5181 (class 0 OID 33968)
-- Dependencies: 230
-- Data for Name: commande_produit; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5175 (class 0 OID 33840)
-- Dependencies: 224
-- Data for Name: image_produit; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (1, 1, 'https://cdn.example.com/bracelet-perles-1.jpg', 1);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (2, 1, 'https://cdn.example.com/bracelet-perles-2.jpg', 2);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (3, 2, 'https://cdn.example.com/boucles-delta-chrome-1.jpg', 3);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (4, 2, 'https://cdn.example.com/boucles-delta-chrome-2.jpg', 4);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (5, 2, 'https://cdn.example.com/boucles-delta-chrome-3.jpg', 5);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (6, 3, 'https://cdn.example.com/pendentif-delta-chrome-1.jpg', 6);
INSERT INTO public.image_produit (id_image, id_produit, url_image, ordre) VALUES (7, 3, 'https://cdn.example.com/pendentif-delta-chrome-2.jpg', 7);


--
-- TOC entry 5179 (class 0 OID 33918)
-- Dependencies: 228
-- Data for Name: liste_envie; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5183 (class 0 OID 34019)
-- Dependencies: 232
-- Data for Name: message_contact; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5177 (class 0 OID 33881)
-- Dependencies: 226
-- Data for Name: panier; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5178 (class 0 OID 33898)
-- Dependencies: 227
-- Data for Name: panier_produit; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5174 (class 0 OID 33818)
-- Dependencies: 223
-- Data for Name: produit; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.produit (id_produit, id_categorie, nom_produit, description, prix, stock) VALUES (1, 3, 'Bracelet Perles', 'Bracelet artisanal fait avec des perles naturelles', '45,99 €', 8);
INSERT INTO public.produit (id_produit, id_categorie, nom_produit, description, prix, stock) VALUES (2, 1, 'Boucles d''oreilles Delta-Chrome', 'Bijoux d''oreilles au design architectural avec finition bi-texture (mate et brillante). Incorpore une fine chaîne pendante pour un look néo-industriel. Matériau : Alliage haute résistance.', '24,90 €', 75);
INSERT INTO public.produit (id_produit, id_categorie, nom_produit, description, prix, stock) VALUES (3, 2, 'Pendentif Delta-Chrome avec chaîne', 'Collier avec pendentif architectural assorti aux boucles d''oreilles Delta-Chrome. Finition bicolore (mate et brillante) et détail de chaînette pendante. Livré avec une chaîne fine en métal argenté de 45 cm.', '27,50 €', 40);


--
-- TOC entry 5176 (class 0 OID 33862)
-- Dependencies: 225
-- Data for Name: promotion; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4954 (class 2606 OID 33765)
-- Name: admin admin_email_admin_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_email_admin_key UNIQUE (email_admin);


--
-- TOC entry 4956 (class 2606 OID 33763)
-- Name: admin admin_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_pkey PRIMARY KEY (id_admin);


--
-- TOC entry 4962 (class 2606 OID 33798)
-- Name: adresse adresse_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.adresse
    ADD CONSTRAINT adresse_pkey PRIMARY KEY (id_adresse);


--
-- TOC entry 4992 (class 2606 OID 34005)
-- Name: avis avis_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT avis_pkey PRIMARY KEY (id_avis);


--
-- TOC entry 4964 (class 2606 OID 33816)
-- Name: categorie categorie_nom_categorie_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_nom_categorie_key UNIQUE (nom_categorie);


--
-- TOC entry 4966 (class 2606 OID 33814)
-- Name: categorie categorie_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorie
    ADD CONSTRAINT categorie_pkey PRIMARY KEY (id_categorie);


--
-- TOC entry 4958 (class 2606 OID 33781)
-- Name: client client_email_client_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_email_client_key UNIQUE (email_client);


--
-- TOC entry 4960 (class 2606 OID 33779)
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id_client);


--
-- TOC entry 4988 (class 2606 OID 33957)
-- Name: commande commande_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_pkey PRIMARY KEY (id_commande);


--
-- TOC entry 4990 (class 2606 OID 33976)
-- Name: commande_produit commande_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT commande_produit_pkey PRIMARY KEY (id_commande, id_produit);


--
-- TOC entry 4972 (class 2606 OID 33855)
-- Name: image_produit image_produit_ordre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_ordre_key UNIQUE (ordre);


--
-- TOC entry 4974 (class 2606 OID 33851)
-- Name: image_produit image_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_pkey PRIMARY KEY (id_image);


--
-- TOC entry 4976 (class 2606 OID 33853)
-- Name: image_produit image_produit_url_image_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT image_produit_url_image_key UNIQUE (url_image);


--
-- TOC entry 4986 (class 2606 OID 33929)
-- Name: liste_envie liste_envie_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT liste_envie_pkey PRIMARY KEY (id_liste_envie);


--
-- TOC entry 4996 (class 2606 OID 34033)
-- Name: message_contact message_contact_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT message_contact_pkey PRIMARY KEY (id_message);


--
-- TOC entry 4980 (class 2606 OID 33892)
-- Name: panier panier_id_session_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_session_key UNIQUE (id_session);


--
-- TOC entry 4982 (class 2606 OID 33890)
-- Name: panier panier_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_pkey PRIMARY KEY (id_panier);


--
-- TOC entry 4984 (class 2606 OID 33906)
-- Name: panier_produit panier_produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT panier_produit_pkey PRIMARY KEY (id_panier, id_produit);


--
-- TOC entry 4968 (class 2606 OID 33833)
-- Name: produit produit_nom_produit_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_nom_produit_key UNIQUE (nom_produit);


--
-- TOC entry 4970 (class 2606 OID 33831)
-- Name: produit produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_pkey PRIMARY KEY (id_produit);


--
-- TOC entry 4978 (class 2606 OID 33874)
-- Name: promotion promotion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT promotion_pkey PRIMARY KEY (id_promotion);


--
-- TOC entry 4994 (class 2606 OID 34007)
-- Name: avis unique_avis_client_produit; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT unique_avis_client_produit UNIQUE (id_client, id_produit);


--
-- TOC entry 4997 (class 2606 OID 33799)
-- Name: adresse fk_adresse_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.adresse
    ADD CONSTRAINT fk_adresse_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- TOC entry 5010 (class 2606 OID 34008)
-- Name: avis fk_avis_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT fk_avis_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- TOC entry 5011 (class 2606 OID 34013)
-- Name: avis fk_avis_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avis
    ADD CONSTRAINT fk_avis_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- TOC entry 5006 (class 2606 OID 33963)
-- Name: commande fk_commande_adresse; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT fk_commande_adresse FOREIGN KEY (id_adresse) REFERENCES public.adresse(id_adresse) ON DELETE RESTRICT;


--
-- TOC entry 5007 (class 2606 OID 33958)
-- Name: commande fk_commande_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT fk_commande_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE RESTRICT;


--
-- TOC entry 5008 (class 2606 OID 33977)
-- Name: commande_produit fk_commande_produit_commande; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT fk_commande_produit_commande FOREIGN KEY (id_commande) REFERENCES public.commande(id_commande) ON DELETE CASCADE;


--
-- TOC entry 5009 (class 2606 OID 33982)
-- Name: commande_produit fk_commande_produit_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande_produit
    ADD CONSTRAINT fk_commande_produit_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE RESTRICT;


--
-- TOC entry 4999 (class 2606 OID 33856)
-- Name: image_produit fk_image_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.image_produit
    ADD CONSTRAINT fk_image_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- TOC entry 5004 (class 2606 OID 33930)
-- Name: liste_envie fk_liste_envie_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT fk_liste_envie_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE CASCADE;


--
-- TOC entry 5005 (class 2606 OID 33935)
-- Name: liste_envie fk_liste_envie_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liste_envie
    ADD CONSTRAINT fk_liste_envie_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- TOC entry 5012 (class 2606 OID 34034)
-- Name: message_contact fk_message_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT fk_message_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE SET NULL;


--
-- TOC entry 5013 (class 2606 OID 34039)
-- Name: message_contact fk_message_commande; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.message_contact
    ADD CONSTRAINT fk_message_commande FOREIGN KEY (num_commande) REFERENCES public.commande(id_commande) ON DELETE SET NULL;


--
-- TOC entry 5001 (class 2606 OID 33893)
-- Name: panier fk_panier_client; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT fk_panier_client FOREIGN KEY (id_client) REFERENCES public.client(id_client) ON DELETE SET NULL;


--
-- TOC entry 5002 (class 2606 OID 33907)
-- Name: panier_produit fk_panier_produit_panier; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT fk_panier_produit_panier FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier) ON DELETE CASCADE;


--
-- TOC entry 5003 (class 2606 OID 33912)
-- Name: panier_produit fk_panier_produit_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier_produit
    ADD CONSTRAINT fk_panier_produit_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


--
-- TOC entry 4998 (class 2606 OID 33834)
-- Name: produit fk_produit_categorie; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT fk_produit_categorie FOREIGN KEY (id_categorie) REFERENCES public.categorie(id_categorie) ON DELETE RESTRICT;


--
-- TOC entry 5000 (class 2606 OID 33875)
-- Name: promotion fk_promotion_produit; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT fk_promotion_produit FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit) ON DELETE CASCADE;


-- Completed on 2026-03-13 11:33:21

--
-- PostgreSQL database dump complete
--

