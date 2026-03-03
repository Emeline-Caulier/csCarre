create or replace view vue_theme_produit
as 
select 
p.id_produit, p.nom_produit, p.prix_produit, p.stock_online, p.description, p.image,
t.id_theme, t.nom_theme, t.date_debut, t.date_fin, 
ty.id_type, ty.nom_type
from produit p
join theme t
on p.id_theme = t.id_theme
join "type" ty 
on ty.id_type = p.id_type;
