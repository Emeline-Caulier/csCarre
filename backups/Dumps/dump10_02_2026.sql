

--
-- TOC entry 220 (class 1259 OID 16391)
-- Name: pays; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pays (
    id_pays integer NOT NULL,
    nom_pays text NOT NULL,
    iso_code text NOT NULL
);


--
-- TOC entry 219 (class 1259 OID 16390)
-- Name: pays_id_pays_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pays_id_pays_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 5030 (class 0 OID 0)
-- Dependencies: 219
-- Name: pays_id_pays_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pays_id_pays_seq OWNED BY public.pays.id_pays;


--
-- TOC entry 222 (class 1259 OID 16403)
-- Name: ville; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ville (
    id_ville integer NOT NULL,
    nom_ville text NOT NULL,
    id_pays integer
);


--
-- TOC entry 221 (class 1259 OID 16402)
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
-- TOC entry 5031 (class 0 OID 0)
-- Dependencies: 221
-- Name: ville_id_ville_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ville_id_ville_seq OWNED BY public.ville.id_ville;


--
-- TOC entry 223 (class 1259 OID 16418)
-- Name: vue_pays_ville; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.vue_pays_ville AS
 SELECT p.id_pays,
    p.nom_pays,
    p.iso_code,
    v.id_ville,
    v.nom_ville
   FROM (public.pays p
     JOIN public.ville v ON ((p.id_pays = v.id_pays)));


--
-- TOC entry 4865 (class 2604 OID 16394)
-- Name: pays id_pays; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pays ALTER COLUMN id_pays SET DEFAULT nextval('public.pays_id_pays_seq'::regclass);


--
-- TOC entry 4866 (class 2604 OID 16406)
-- Name: ville id_ville; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville ALTER COLUMN id_ville SET DEFAULT nextval('public.ville_id_ville_seq'::regclass);


--
-- TOC entry 5021 (class 0 OID 16391)
-- Dependencies: 220
-- Data for Name: pays; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5023 (class 0 OID 16403)
-- Dependencies: 222
-- Data for Name: ville; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 5032 (class 0 OID 0)
-- Dependencies: 219
-- Name: pays_id_pays_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.pays_id_pays_seq', 1, false);


--
-- TOC entry 5033 (class 0 OID 0)
-- Dependencies: 221
-- Name: ville_id_ville_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ville_id_ville_seq', 1, false);


--
-- TOC entry 4868 (class 2606 OID 16401)
-- Name: pays pays_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pays
    ADD CONSTRAINT pays_pkey PRIMARY KEY (id_pays);


--
-- TOC entry 4870 (class 2606 OID 16412)
-- Name: ville ville_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_pkey PRIMARY KEY (id_ville);


--
-- TOC entry 4871 (class 2606 OID 16413)
-- Name: ville ville_id_pays_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_id_pays_fkey FOREIGN KEY (id_pays) REFERENCES public.pays(id_pays);


-- Completed on 2026-02-10 09:59:11

--
-- PostgreSQL database dump complete
--

\unrestrict p56c81MVBriuIO5MIU3KJEjyulAIVmHTMFnLBAwb7VsK2K8hDVkiuvMJcZfbIBN

