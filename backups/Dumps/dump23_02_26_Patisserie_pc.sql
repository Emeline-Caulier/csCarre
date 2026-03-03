--
-- PostgreSQL database dump
--

\restrict H2b1cAcygHRQocCWkFwMrMQvUEmtQhk8kJClWiaAe0olhrZhNfiZ9va6AqSt8GJ

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-02-23 10:57:27

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
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 230 (class 1259 OID 16780)
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
-- TOC entry 229 (class 1259 OID 16779)
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
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 229
-- Name: client_id_client_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.client_id_client_seq OWNED BY public.client.id_client;


--
-- TOC entry 234 (class 1259 OID 16826)
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
-- TOC entry 233 (class 1259 OID 16825)
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
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 233
-- Name: commande_id_commande_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.commande_id_commande_seq OWNED BY public.commande.id_commande;


--
-- TOC entry 226 (class 1259 OID 16735)
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
-- TOC entry 225 (class 1259 OID 16734)
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
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 225
-- Name: decoration_id_decoration_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.decoration_id_decoration_seq OWNED BY public.decoration.id_decoration;


--
-- TOC entry 235 (class 1259 OID 16846)
-- Name: decoration_panier; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.decoration_panier (
    id_decoration integer NOT NULL,
    id_panier integer NOT NULL,
    quantite integer NOT NULL
);


--
-- TOC entry 232 (class 1259 OID 16804)
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
-- TOC entry 231 (class 1259 OID 16803)
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
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 231
-- Name: panier_id_panier_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.panier_id_panier_seq OWNED BY public.panier.id_panier;


--
-- TOC entry 228 (class 1259 OID 16752)
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
-- TOC entry 227 (class 1259 OID 16751)
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
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 227
-- Name: produit_id_produit_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.produit_id_produit_seq OWNED BY public.produit.id_produit;


--
-- TOC entry 220 (class 1259 OID 16696)
-- Name: theme; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.theme (
    id_theme integer NOT NULL,
    nom_theme text NOT NULL,
    date_debut date,
    date_fin date
);


--
-- TOC entry 219 (class 1259 OID 16695)
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
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 219
-- Name: theme_id_theme_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.theme_id_theme_seq OWNED BY public.theme.id_theme;


--
-- TOC entry 224 (class 1259 OID 16722)
-- Name: type; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.type (
    id_type integer NOT NULL,
    nom_type text NOT NULL
);


--
-- TOC entry 223 (class 1259 OID 16721)
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
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 223
-- Name: type_id_type_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.type_id_type_seq OWNED BY public.type.id_type;


--
-- TOC entry 222 (class 1259 OID 16709)
-- Name: ville; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ville (
    id_ville integer NOT NULL,
    nom_ville text NOT NULL,
    code_postal text NOT NULL,
    livraison boolean NOT NULL
);


--
-- TOC entry 221 (class 1259 OID 16708)
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
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 221
-- Name: ville_id_ville_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;


--
-- TOC entry 236 (class 1259 OID 16864)
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
-- TOC entry 4904 (class 2604 OID 16783)
-- Name: client id_client; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client ALTER COLUMN id_client SET DEFAULT nextval('public.client_id_client_seq'::regclass);


--
-- TOC entry 4906 (class 2604 OID 16829)
-- Name: commande id_commande; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande ALTER COLUMN id_commande SET DEFAULT nextval('public.commande_id_commande_seq'::regclass);


--
-- TOC entry 4902 (class 2604 OID 16738)
-- Name: decoration id_decoration; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration ALTER COLUMN id_decoration SET DEFAULT nextval('public.decoration_id_decoration_seq'::regclass);


--
-- TOC entry 4905 (class 2604 OID 16807)
-- Name: panier id_panier; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier ALTER COLUMN id_panier SET DEFAULT nextval('public.panier_id_panier_seq'::regclass);


--
-- TOC entry 4903 (class 2604 OID 16755)
-- Name: produit id_produit; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit ALTER COLUMN id_produit SET DEFAULT nextval('public.produit_id_produit_seq'::regclass);


--
-- TOC entry 4899 (class 2604 OID 16699)
-- Name: theme id_theme; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme ALTER COLUMN id_theme SET DEFAULT nextval('public.theme_id_theme_seq'::regclass);


--
-- TOC entry 4901 (class 2604 OID 16725)
-- Name: type id_type; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type ALTER COLUMN id_type SET DEFAULT nextval('public.type_id_type_seq'::regclass);


--
-- TOC entry 4900 (class 2604 OID 16712)
-- Name: ville id_ville; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville ALTER COLUMN id_ville SET DEFAULT nextval('public.ville_id_ville_seq'::regclass);


--
-- TOC entry 5102 (class 0 OID 16780)
-- Dependencies: 230
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5106 (class 0 OID 16826)
-- Dependencies: 234
-- Data for Name: commande; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5098 (class 0 OID 16735)
-- Dependencies: 226
-- Data for Name: decoration; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (1, 'Paillettes dorées', true, '2,50 €', 100, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (2, 'Figurine Mariage', false, '15,00 €', 10, true);
INSERT INTO public.decoration (id_decoration, nom_decoration, comestible, prix_decoration, stock_decoration, montrer) VALUES (3, 'Fleurs en sucre', true, '5,00 €', 50, true);


--
-- TOC entry 5107 (class 0 OID 16846)
-- Dependencies: 235
-- Data for Name: decoration_panier; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5104 (class 0 OID 16804)
-- Dependencies: 232
-- Data for Name: panier; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5100 (class 0 OID 16752)
-- Dependencies: 228
-- Data for Name: produit; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (7, 'Forêt Noire', '35,00 €', 10, 'Délicieux gâteau chocolat et cerise', 'foret_noire.jpg', 1, 18);
INSERT INTO public.produit (id_produit, nom_produit, prix_produit, stock_online, description, image, id_type, id_theme) VALUES (8, 'Cupcake Vanille des îles', '4,50 €', 25, 'Cupcake léger à la vanille', 'cupcake_vanille.png', 2, 13);


--
-- TOC entry 5092 (class 0 OID 16696)
-- Dependencies: 220
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


--
-- TOC entry 5096 (class 0 OID 16722)
-- Dependencies: 224
-- Data for Name: type; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.type (id_type, nom_type) VALUES (1, 'Gâteau d''anniversaire');
INSERT INTO public.type (id_type, nom_type) VALUES (2, 'Cupcake');
INSERT INTO public.type (id_type, nom_type) VALUES (3, 'Macaron');


--
-- TOC entry 5094 (class 0 OID 16709)
-- Dependencies: 222
-- Data for Name: ville; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (1, 'Mons', '7000', true);
INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (2, 'Namur', '5000', true);
INSERT INTO public.ville (id_ville, nom_ville, code_postal, livraison) VALUES (3, 'Bruxelles', '1000', false);


--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 229
-- Name: client_id_client_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.client_id_client_seq', 1, false);


--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 233
-- Name: commande_id_commande_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.commande_id_commande_seq', 1, false);


--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 225
-- Name: decoration_id_decoration_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.decoration_id_decoration_seq', 3, true);


--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 231
-- Name: panier_id_panier_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.panier_id_panier_seq', 1, false);


--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 227
-- Name: produit_id_produit_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.produit_id_produit_seq', 8, true);


--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 219
-- Name: theme_id_theme_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.theme_id_theme_seq', 21, true);


--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 223
-- Name: type_id_type_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.type_id_type_seq', 3, true);


--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 221
-- Name: ville_id_ville_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ville_id_ville_seq', 3, true);


--
-- TOC entry 4926 (class 2606 OID 16797)
-- Name: client client_email_client_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_email_client_key UNIQUE (email_client);


--
-- TOC entry 4928 (class 2606 OID 16795)
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id_client);


--
-- TOC entry 4932 (class 2606 OID 16840)
-- Name: commande commande_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_pkey PRIMARY KEY (id_commande);


--
-- TOC entry 4918 (class 2606 OID 16750)
-- Name: decoration decoration_nom_decoration_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration
    ADD CONSTRAINT decoration_nom_decoration_key UNIQUE (nom_decoration);


--
-- TOC entry 4934 (class 2606 OID 16853)
-- Name: decoration_panier decoration_panier_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_pkey PRIMARY KEY (id_decoration, id_panier);


--
-- TOC entry 4920 (class 2606 OID 16748)
-- Name: decoration decoration_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration
    ADD CONSTRAINT decoration_pkey PRIMARY KEY (id_decoration);


--
-- TOC entry 4930 (class 2606 OID 16814)
-- Name: panier panier_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_pkey PRIMARY KEY (id_panier);


--
-- TOC entry 4922 (class 2606 OID 16768)
-- Name: produit produit_nom_produit_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_nom_produit_key UNIQUE (nom_produit);


--
-- TOC entry 4924 (class 2606 OID 16766)
-- Name: produit produit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_pkey PRIMARY KEY (id_produit);


--
-- TOC entry 4908 (class 2606 OID 16707)
-- Name: theme theme_nom_theme_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_nom_theme_key UNIQUE (nom_theme);


--
-- TOC entry 4910 (class 2606 OID 16705)
-- Name: theme theme_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_pkey PRIMARY KEY (id_theme);


--
-- TOC entry 4914 (class 2606 OID 16733)
-- Name: type type_nom_type_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type
    ADD CONSTRAINT type_nom_type_key UNIQUE (nom_type);


--
-- TOC entry 4916 (class 2606 OID 16731)
-- Name: type type_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type
    ADD CONSTRAINT type_pkey PRIMARY KEY (id_type);


--
-- TOC entry 4912 (class 2606 OID 16720)
-- Name: ville ville_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_pkey PRIMARY KEY (id_ville);


--
-- TOC entry 4937 (class 2606 OID 16798)
-- Name: client client_id_ville_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_id_ville_fkey FOREIGN KEY (id_ville) REFERENCES public.ville(id_ville);


--
-- TOC entry 4940 (class 2606 OID 16841)
-- Name: commande commande_id_panier_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commande
    ADD CONSTRAINT commande_id_panier_fkey FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier);


--
-- TOC entry 4941 (class 2606 OID 16854)
-- Name: decoration_panier decoration_panier_id_decoration_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_id_decoration_fkey FOREIGN KEY (id_decoration) REFERENCES public.decoration(id_decoration);


--
-- TOC entry 4942 (class 2606 OID 16859)
-- Name: decoration_panier decoration_panier_id_panier_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.decoration_panier
    ADD CONSTRAINT decoration_panier_id_panier_fkey FOREIGN KEY (id_panier) REFERENCES public.panier(id_panier);


--
-- TOC entry 4938 (class 2606 OID 16815)
-- Name: panier panier_id_client_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_client_fkey FOREIGN KEY (id_client) REFERENCES public.client(id_client);


--
-- TOC entry 4939 (class 2606 OID 16820)
-- Name: panier panier_id_produit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.panier
    ADD CONSTRAINT panier_id_produit_fkey FOREIGN KEY (id_produit) REFERENCES public.produit(id_produit);


--
-- TOC entry 4935 (class 2606 OID 16774)
-- Name: produit produit_id_theme_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_id_theme_fkey FOREIGN KEY (id_theme) REFERENCES public.theme(id_theme);


--
-- TOC entry 4936 (class 2606 OID 16769)
-- Name: produit produit_id_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produit
    ADD CONSTRAINT produit_id_type_fkey FOREIGN KEY (id_type) REFERENCES public.type(id_type);


-- Completed on 2026-02-23 10:57:27

--
-- PostgreSQL database dump complete
--

\unrestrict H2b1cAcygHRQocCWkFwMrMQvUEmtQhk8kJClWiaAe0olhrZhNfiZ9va6AqSt8GJ

