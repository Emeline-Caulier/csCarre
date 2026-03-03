create or replace view vue_pays_ville as select
p.id_pays, p.nom_pays, p.iso_code, v.id_ville, v.nom_ville
from pays p
join
ville v
on p.id_pays = v.id_pays;