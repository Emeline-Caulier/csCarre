
CREATE OR REPLACE VIEW public.v_commandes_client
 AS
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
   FROM commande cmd
     JOIN client cl ON cmd.id_client = cl.id_client
     JOIN adresse addr ON cmd.id_adresse = addr.id_adresse
  ORDER BY cmd.date_commande DESC;

