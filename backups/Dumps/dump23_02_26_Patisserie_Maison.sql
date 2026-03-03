--
-- PostgreSQL database dump
--

\restrict ZxQ6bCYcOXepQcs0AawxVUyWk41MRF1p0XyLBSKKVnIR4cC3IePaMm96pvKvXv1

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-02-23 11:17:43

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

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 16493)
-- Name: client; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.client (
    id_client integer NOT NULL,
    email_client text NOT NULL,
    nom_client text NOT NULL,
    prenom_client text NOT NULL,
    adresse_client text NOT NULL,
    telephone_client text NOT NULL,
    numero_client text NOT NULL,
    id_ville integer NOT NULL
);


--
-- TOC entry 220 (class 1259 OID 16506)
-- Name: client_id_client_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.client_id_client_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 220
-- Name: client_id_client_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.client_id_client_seq OWNED BY public.client.id_client;


--
-- TOC entry 221 (class 1259 OID 16507)
-- Name: commande; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.commande (
    id_commande integer NOT NULL,
    date_commande date NOT NULL,
    statut text NOT NULL,
    retrait boolean NOT NULL,
    paye boolean NOT NULL,
    date_retrait date NOT NULL,
    id_panier integer NOT NULL
);


--
-- TOC entry 222 (class 1259 OID 16519)
-- Name: commande_id_commande_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.commande_id_commande_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 222
-- Name: commande_id_commande_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.commande_id_commande_seq OWNED BY public.commande.id_commande;


--
-- TOC entry 223 (class 1259 OID 16520)
-- Name: decoration; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.decoration (
    id_decoration integer NOT NULL,
    nom_decoration text NOT NULL,
    comestible boolean NOT NULL,
    prix_decoration money NOT NULL,
    stock_decoration integer NOT NULL,
    montrer boolean NOT NULL
);


--
-- TOC entry 224 (class 1259 OID 16531)
-- Name: decoration_id_decoration_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.decoration_id_decoration_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 224
-- Name: decoration_id_decoration_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.decoration_id_decoration_seq OWNED BY public.decoration.id_decoration;


--
-- TOC entry 225 (class 1259 OID 16532)
-- Name: decoration_panier; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.decoration_panier (
    id_decoration integer NOT NULL,
    id_panier integer NOT NULL,
    quantite integer NOT NULL
);


--
-- TOC entry 226 (class 1259 OID 16538)
-- Name: panier; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.panier (
    id_panier integer NOT NULL,
    quantite integer NOT NULL,
    prix_panier money NOT NULL,
    id_client integer NOT NULL,
    id_produit integer NOT NULL
);


--
-- TOC entry 227 (class 1259 OID 16546)
-- Name: panier_id_panier_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.panier_id_panier_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 227
-- Name: panier_id_panier_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.panier_id_panier_seq OWNED BY public.panier.id_panier;


--
-- TOC entry 228 (class 1259 OID 16547)
-- Name: produit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.produit (
    id_produit integer NOT NULL,
    nom_produit text NOT NULL,
    prix_produit money NOT NULL,
    stock_online integer NOT NULL,
    description text NOT NULL,
    image text,
    id_type integer NOT NULL,
    id_theme integer NOT NULL
);


--
-- TOC entry 229 (class 1259 OID 16559)
-- Name: produit_id_produit_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.produit_id_produit_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 229
-- Name: produit_id_produit_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.produit_id_produit_seq OWNED BY public.produit.id_produit;


--
-- TOC entry 230 (class 1259 OID 16560)
-- Name: theme; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.theme (
    id_theme integer NOT NULL,
    nom_theme text NOT NULL,
    date_debut date,
    date_fin date
);


--
-- TOC entry 231 (class 1259 OID 16567)
-- Name: theme_id_theme_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.theme_id_theme_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 231
-- Name: theme_id_theme_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.theme_id_theme_seq OWNED BY public.theme.id_theme;


--
-- TOC entry 232 (class 1259 OID 16568)
-- Name: type; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.type (
    id_type integer NOT NULL,
    nom_type text NOT NULL
);


--
-- TOC entry 233 (class 1259 OID 16575)
-- Name: type_id_type_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.type_id_type_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 233
-- Name: type_id_type_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.type_id_type_seq OWNED BY public.type.id_type;


--
-- TOC entry 234 (class 1259 OID 16576)
-- Name: ville; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ville (
    id_ville integer NOT NULL,
    nom_ville text NOT NULL,
    code_postal text NOT NULL,
    livraison boolean NOT NULL
);


--
-- TOC entry 235 (class 1259 OID 16585)
-- Name: ville_id_ville_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ville_id_ville_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 235
-- Name: ville_id_ville_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;


--
-- TOC entry 237 (class 1259 OID 16675)
-- Name: vue_decoration_panier; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.vue_decoration_panier AS
 SELECT dp.id_panier,
    d.id_decoration,
    d.nom_decoration,
    d.prix_decoration,
    dp.quantite,
    (d.prix_decoration * dp.quantite) AS sous_total_deco
   FROM (public.decoration_panier dp
     JOIN public.decoration d ON ((dp.id_decoration = d.id_decoration)));


--
-- TOC entry 236 (class 1259 OID 16586)
-- Name: vue_theme_produit; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.vue_theme_produit AS
 SELECT p.id_produit,
    p.nom_produit,
    p.prix_produit,
    p.stock_online,
    p.description,
    p.image,
    t.id_theme,
    t.nom_theme,
    t.date_debut,
    t.date_fin,
    ty.id_type,
    ty.nom_type
   FROM ((public.produit p
     JOIN public.theme t ON ((p.id_theme = t.id_theme)))
     JOIN public.type ty ON ((ty.id_type = p.id_type)));


--
-- TOC entry 4903 (class 2604 OID 16591)
-- Name: client id_client; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client ALTER COLUMN id_client SET DEFAULT nextval('public.client_id_client_seq'::regclass);


--
-- TOC entry 4904 (class 2604 OID 16592)
-- Name: commande id_commande; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande ALTER COLUMN id_commande SET DEFAULT nextval('public.commande_id_commande_seq'::regclass);


--
-- TOC entry 4905 (class 2604 OID 16593)
-- Name: decoration id_decoration; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration ALTER COLUMN id_decoration SET DEFAULT nextval('public.decoration_id_decoration_seq'::regclass);


--
-- TOC entry 4906 (class 2604 OID 16594)
-- Name: panier id_panier; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier ALTER COLUMN id_panier SET DEFAULT nextval('public.panier_id_panier_seq'::regclass);


--
-- TOC entry 4907 (class 2604 OID 16595)
-- Name: produit id_produit; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit ALTER COLUMN id_produit SET DEFAULT nextval('public.produit_id_produit_seq'::regclass);


--
-- TOC entry 4908 (class 2604 OID 16596)
-- Name: theme id_theme; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme ALTER COLUMN id_theme SET DEFAULT nextval('public.theme_id_theme_seq'::regclass);


--
-- TOC entry 4909 (class 2604 OID 16597)
-- Name: type id_type; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type ALTER COLUMN id_type SET DEFAULT nextval('public.type_id_type_seq'::regclass);


--
-- TOC entry 4910 (class 2604 OID 16598)
-- Name: ville id_ville; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville ALTER COLUMN id_ville SET DEFAULT nextval('public.ville_id_ville_seq'::regclass);


--
-- TOC entry 5096 (class 0 OID 16493)
-- Dependencies: 219
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.client (id_client, email_client, nom_client, prenom_client, adresse_client, telephone_client, numero_client, id_ville) VALUES (1, 'jean.dupont@email.be', 'Dupont', 'Jean', 'Rue de la Station 12', '0475123456', 'CL001', 1);
INSERT INTO public.client (id_client, email_client, nom_client, prenom_client, adresse_client, telephone_client, numero_client, id_ville) VALUES (2, 'marie.dubois@email.be', 'Dubois', 'Marie', 'Avenue Louise 45', '0486987654', 'CL002', 2);
INSERT INTO public.client (id_client, email_client, nom_client, prenom_client, adresse_client, telephone_client, numero_client, id_ville) VALUES (3, 'pierre.martin@email.be', 'Martin', 'Pierre', 'Boulevard Tirou 8', '0499112233', 'CL003', 4);
INSERT INTO public.client (id_client, email_client, nom_client, prenom_client, adresse_client, telephone_client, numero_client, id_ville) VALUES (4, 'sophie.le@email.be', 'Lefebvre', 'Sophie', 'Grand Place 1', '0477445566', 'CL004', 1);


--
-- TOC entry 5098 (class 0 OID 16507)
-- Dependencies: 221
-- Data for Name: commande; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.commande (id_commande, date_commande, statut, retrait, paye, date_retrait, id_panier) VALUES (1, '2026-02-23', 'En préparation', true, true, '2026-02-25', 1);
INSERT INTO public.commande (id_commande, date_commande, statut, retrait, paye, date_retrait, id_panier) VALUES (2, '2026-02-23', 'Validée', true, true, '2026-02-26', 2);


--
-- TOC entry 5100 (class 0 OID 16520)
-- Dependencies: 223
-- Data for Name: decoration; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (1, 'Paillettes dorées', true, '2,50 €', 100, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (2, 'Figurine Mariage', false, '15,00 €', 10, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (3, 'Fleurs en sucre', true, '5,00 €', 50, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (4, 'Ruban Personnalisé', false, '3,50 €', 100, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (5, 'Perles de sucre argentées', true, '1,50 €', 200, true);


--
-- TOC entry 5102 (class 0 OID 16532)
-- Dependencies: 225
-- Data for Name: decoration_panier; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.decoration_panier (id_decoration, id_panier, quantite) VALUES (1, 1, 2);
INSERT INTO public.decoration_panier (id_decoration, id_panier, quantite) VALUES (5, 2, 1);


--
-- TOC entry 5103 (class 0 OID 16538)
-- Dependencies: 226
-- Data for Name: panier; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.panier (id_panier, quantite, prix_panier, id_client, id_produit) VALUES (1, 1, '35,00 €', 1, 7);
INSERT INTO public.panier (id_panier, quantite, prix_panier, id_client, id_produit) VALUES (2, 2, '11,00 €', 4, 10);
INSERT INTO public.panier (id_panier, quantite, prix_panier, id_client, id_produit) VALUES (3, 1, '5,00 €', 3, 13);


--
-- TOC entry 5105 (class 0 OID 16547)
-- Dependencies: 228
-- Data for Name: produit; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (7, 'Forêt Noire', '35,00 €', 10, 'Délicieux gâteau chocolat et cerise', 'foret_noire.jpg', 1, 18);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (8, 'Cupcake Vanille des îles', '4,50 €', 25, 'Cupcake léger à la vanille', 'cupcake_vanille.png', 2, 13);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (9, 'Coffret Macarons Saveurs', '12,00 €', 20, 'Assortiment de 6 macarons artisanaux', 'macarons_box.jpg', 3, 18);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (10, 'Tartelette Fraise', '5,50 €', 15, 'Fraises fraîches sur crème pâtissière', 'tarte_fraise.jpg', 1, 19);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (11, 'Cupcake Chocolat-Noisette', '4,80 €', 30, 'Cœur fondant noisette et topping chocolat', 'cupcake_choc_noisette.png', 2, 18);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (12, 'Tarte aux Pommes Classique', '18,00 €', 15, 'Tarte traditionnelle aux pommes caramélisées', 'tarte_pommes.jpg', 4, 18);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (13, 'Cupcake Citrouille Épicée', '5,00 €', 40, 'Saveurs d''automne et crème cannelle', 'cupcake_halloween.png', 2, 21);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (14, 'Éclair Café', '3,80 €', 25, 'Crème onctueuse au café pur arabica', 'eclair_cafe.jpg', 2, 18);


--
-- TOC entry 5107 (class 0 OID 16560)
-- Dependencies: 230
-- Data for Name: theme; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (13, 'Paques', '2026-03-01', '2026-04-30');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (14, 'Noel', '2026-11-15', '2027-01-15');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (15, 'Carnaval', '2026-02-01', '2026-03-10');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (16, 'Fête des mères', '2026-04-10', '2026-05-31');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (17, 'Fête des pères', '2026-12-05', '2027-01-06');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (18, 'Standard', NULL, NULL);
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (19, 'Printemps 2026', '2026-03-21', '2026-06-20');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (20, 'Été 2026', '2026-06-21', '2026-09-21');
INSERT INTO public.theme (id_theme, nom_theme, date_debut, date_fin) VALUES (21, 'Halloween', '2026-10-15', '2026-11-05');


--
-- TOC entry 5109 (class 0 OID 16568)
-- Dependencies: 232
-- Data for Name: type; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.type (id_type, nom_type) VALUES (1, 'Gâteau d''anniversaire');
INSERT INTO public.type (id_type, nom_type) VALUES (2, 'Cupcake');
INSERT INTO public.type (id_type, nom_type) VALUES (3, 'Macaron');
INSERT INTO public.type (id_type, nom_type) VALUES (4, 'Tarte');


--
-- TOC entry 5111 (class 0 OID 16576)
-- Dependencies: 234
-- Data for Name: ville; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (1, 'Mons', '7000', true);
INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (2, 'Namur', '5000', true);
INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (3, 'Bruxelles', '1000', false);
INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (4, 'Charleroi', '6000', true);


--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 220
-- Name: client_id_client_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.client_id_client_seq', 1, false);


--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 222
-- Name: commande_id_commande_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.commande_id_commande_seq', 1, false);


--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 224
-- Name: decoration_id_decoration_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.decoration_id_decoration_seq', 3, true);


--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 227
-- Name: panier_id_panier_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.panier_id_panier_seq', 1, false);


--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 229
-- Name: produit_id_produit_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.produit_id_produit_seq', 8, true);


--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 231
-- Name: theme_id_theme_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.theme_id_theme_seq', 21, true);


--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 233
-- Name: type_id_type_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.type_id_type_seq', 3, true);


--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 235
-- Name: ville_id_ville_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ville_id_ville_seq', 3, true);


--
-- TOC entry 4912 (class 2606 OID 16600)
-- Name: client client_email_client_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_email_client_key UNIQUE (email_client);


--
-- TOC entry 4914 (class 2606 OID 16602)
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id_client);


--
-- TOC entry 4916 (class 2606 OID 16604)
-- Name: commande commande_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_pkey PRIMARY KEY (id_commande);


--
-- TOC entry 4918 (class 2606 OID 16606)
-- Name: decoration decoration_nom_decoration_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration
    ADD CONSTRAINT decoration_nom_decoration_key UNIQUE (nom_decoration);


--
-- TOC entry 4922 (class 2606 OID 16608)
-- Name: decoration_panier decoration_panier_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_pkey PRIMARY KEY (id_decoration, id_panier);


--
-- TOC entry 4920 (class 2606 OID 16610)
-- Name: decoration decoration_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration
    ADD CONSTRAINT decoration_pkey PRIMARY KEY (id_decoration);


--
-- TOC entry 4924 (class 2606 OID 16612)
-- Name: panier panier_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_pkey PRIMARY KEY (id_panier);


--
-- TOC entry 4926 (class 2606 OID 16614)
-- Name: produit produit_nom_produit_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_nom_produit_key UNIQUE (nom_produit);


--
-- TOC entry 4928 (class 2606 OID 16616)
-- Name: produit produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_pkey PRIMARY KEY (id_produit);


--
-- TOC entry 4930 (class 2606 OID 16618)
-- Name: theme theme_nom_theme_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_nom_theme_key UNIQUE (nom_theme);


--
-- TOC entry 4932 (class 2606 OID 16620)
-- Name: theme theme_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_pkey PRIMARY KEY (id_theme);


--
-- TOC entry 4934 (class 2606 OID 16622)
-- Name: type type_nom_type_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type
    ADD CONSTRAINT type_nom_type_key UNIQUE (nom_type);


--
-- TOC entry 4936 (class 2606 OID 16624)
-- Name: type type_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type
    ADD CONSTRAINT type_pkey PRIMARY KEY (id_type);


--
-- TOC entry 4938 (class 2606 OID 16626)
-- Name: ville ville_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_pkey PRIMARY KEY (id_ville);


--
-- TOC entry 4939 (class 2606 OID 16627)
-- Name: client client_id_ville_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_id_ville_fkey FOREIGN KEY (id_ville) REFERENCES public.ville(id_ville);


--
-- TOC entry 4940 (class 2606 OID 16632)
-- Name: commande commande_id_panier_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_id_panier_fkey FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier);


--
-- TOC entry 4941 (class 2606 OID 16637)
-- Name: decoration_panier decoration_panier_id_decoration_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_id_decoration_fkey FOREIGN KEY (id_decoration) REFERENCES public.decoration(id_decoration);


--
-- TOC entry 4942 (class 2606 OID 16642)
-- Name: decoration_panier decoration_panier_id_panier_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_id_panier_fkey FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier);


--
-- TOC entry 4943 (class 2606 OID 16647)
-- Name: panier panier_id_client_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_client_fkey FOREIGN KEY (id_client) REFERENCES public.client(id_client);


--
-- TOC entry 4944 (class 2606 OID 16652)
-- Name: panier panier_id_produit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_produit_fkey FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit);


--
-- TOC entry 4945 (class 2606 OID 16657)
-- Name: produit produit_id_theme_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_id_theme_fkey FOREIGN KEY (id_theme) REFERENCES public.theme(id_theme);


--
-- TOC entry 4946 (class 2606 OID 16662)
-- Name: produit produit_id_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_id_type_fkey FOREIGN KEY (id_type) REFERENCES public.type(id_type);


-- Completed on 2026-02-23 11:17:43

--
-- PostgreSQL database dump complete
--

\unrestrict ZxQ6bCYcOXepQcs0AawxVUyWk41MRF1p0XyLBSKKVnIR4cC3IePaMm96pvKvXv1

