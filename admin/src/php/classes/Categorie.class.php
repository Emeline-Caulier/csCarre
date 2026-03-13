<?php

declare (strict_types=1);

class Categorie
{
    public int $id_categorie;
    public string $nom_categorie;

    public function __construct(
        int $id_categorie,
        string $nom_categorie
    )
    {
        $this->id_categorie = $id_categorie;
        $this->nom_categorie = $nom_categorie;
    }
}
