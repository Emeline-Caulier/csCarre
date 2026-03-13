
CREATE OR REPLACE VIEW public.v_messages_contact_non_lus
 AS
 SELECT mc.id_message,
    mc.nom_contact,
    mc.email_contact,
    mc.sujet,
    mc.date_message,
    cl.nom_client,
    cl.prenom_client,
    cmd.id_commande
   FROM message_contact mc
     LEFT JOIN client cl ON mc.id_client = cl.id_client
     LEFT JOIN commande cmd ON mc.num_commande = cmd.id_commande
  WHERE mc.lu = false
  ORDER BY mc.date_message DESC;


