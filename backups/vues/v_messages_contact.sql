
CREATE OR REPLACE VIEW public.v_messages_contact
 AS
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
   FROM message_contact mc
     LEFT JOIN client cl ON mc.id_client = cl.id_client
     LEFT JOIN commande cmd ON mc.num_commande = cmd.id_commande
  ORDER BY mc.date_message DESC;


