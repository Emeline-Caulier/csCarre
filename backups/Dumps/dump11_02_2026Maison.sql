--
-- PostgreSQL database dump
--

\restrict EyFITK7aOCe1SFXfYtKhS5IEDsEF5Ho5Whe8SASLxx87B69WTaXAsTeWsbX6i3Y

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-02-11 20:15:54

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
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 5029 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 16390)
-- Name: pays; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.pays (
    id_pays integer NOT NULL,
    nom_pays text NOT NULL,
    iso_code text NOT NULL
);


ALTER TABLE public.pays OWNER TO anonyme;

--
-- TOC entry 220 (class 1259 OID 16398)
-- Name: pays_id_pays_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

CREATE SEQUENCE public.pays_id_pays_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pays_id_pays_seq OWNER TO anonyme;

--
-- TOC entry 5030 (class 0 OID 0)
-- Dependencies: 220
-- Name: pays_id_pays_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: anonyme
--

ALTER SEQUENCE public.pays_id_pays_seq OWNED BY public.pays.id_pays;


--
-- TOC entry 221 (class 1259 OID 16399)
-- Name: ville; Type: TABLE; Schema: public; Owner: anonyme
--

CREATE TABLE public.ville (
    id_ville integer NOT NULL,
    nom_ville text NOT NULL,
    id_pays integer
);


ALTER TABLE public.ville OWNER TO anonyme;

--
-- TOC entry 222 (class 1259 OID 16406)
-- Name: ville_id_ville_seq; Type: SEQUENCE; Schema: public; Owner: anonyme
--

CREATE SEQUENCE public.ville_id_ville_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ville_id_ville_seq OWNER TO anonyme;

--
-- TOC entry 5031 (class 0 OID 0)
-- Dependencies: 222
-- Name: ville_id_ville_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: anonyme
--

ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;


--
-- TOC entry 223 (class 1259 OID 16407)
-- Name: vue_pays_ville; Type: VIEW; Schema: public; Owner: anonyme
--

CREATE VIEW public.vue_pays_ville AS
 SELECT p.id_pays,
    p.nom_pays,
    p.iso_code,
    v.id_ville,
    v.nom_ville
   FROM (public.pays p
     JOIN public.ville v ON ((p.id_pays = v.id_pays)));


ALTER VIEW public.vue_pays_ville OWNER TO anonyme;

--
-- TOC entry 4865 (class 2604 OID 16411)
-- Name: pays id_pays; Type: DEFAULT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.pays ALTER COLUMN id_pays SET DEFAULT nextval('public.pays_id_pays_seq'::regclass);


--
-- TOC entry 4866 (class 2604 OID 16412)
-- Name: ville id_ville; Type: DEFAULT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.ville ALTER COLUMN id_ville SET DEFAULT nextval('public.ville_id_ville_seq'::regclass);


--
-- TOC entry 5020 (class 0 OID 16390)
-- Dependencies: 219
-- Data for Name: pays; Type: TABLE DATA; Schema: public; Owner: anonyme
--



--
-- TOC entry 5022 (class 0 OID 16399)
-- Dependencies: 221
-- Data for Name: ville; Type: TABLE DATA; Schema: public; Owner: anonyme
--



--
-- TOC entry 5032 (class 0 OID 0)
-- Dependencies: 220
-- Name: pays_id_pays_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.pays_id_pays_seq', 1, false);


--
-- TOC entry 5033 (class 0 OID 0)
-- Dependencies: 222
-- Name: ville_id_ville_seq; Type: SEQUENCE SET; Schema: public; Owner: anonyme
--

SELECT pg_catalog.setval('public.ville_id_ville_seq', 1, false);


--
-- TOC entry 4868 (class 2606 OID 16414)
-- Name: pays pays_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.pays
    ADD CONSTRAINT pays_pkey PRIMARY KEY (id_pays);


--
-- TOC entry 4870 (class 2606 OID 16416)
-- Name: ville ville_pkey; Type: CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_pkey PRIMARY KEY (id_ville);


--
-- TOC entry 4871 (class 2606 OID 16417)
-- Name: ville ville_id_pays_fkey; Type: FK CONSTRAINT; Schema: public; Owner: anonyme
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_id_pays_fkey FOREIGN KEY (id_pays) REFERENCES public.pays(id_pays);


-- Completed on 2026-02-11 20:15:55

--
-- PostgreSQL database dump complete
--

\unrestrict EyFITK7aOCe1SFXfYtKhS5IEDsEF5Ho5Whe8SASLxx87B69WTaXAsTeWsbX6i3Y

