CREATE OR REPLACE FUNCTION get_admin(
    p_email_admin TEXT,
    p_mot_de_passe TEXT
)
RETURNS TABLE (
    id_admin INT,
    nom_admin TEXT,
    prenom_admin TEXT,
    email_admin TEXT
) AS $$
BEGIN
    RETURN QUERY 
    SELECT a.id_admin, a.nom_admin, a.prenom_admin, a.email_admin 
    FROM admin a 
    WHERE a.email_admin = p_email_admin
      AND a.mot_de_passe = p_mot_de_passe;
END;
$$ LANGUAGE plpgsql STABLE;