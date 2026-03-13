<?php

declare (strict_types=1);

class Admin
{
    public int $id_admin;
    public string $prenom_admin;
    public string $nom_admin;
    public string $email_admin;

    public function __construct(
        int $id_admin,
        string $prenom_admin,
        string $nom_admin,
        string $email_admin
    )
    {
        $this->id_admin = $id_admin;
        $this->prenom_admin = $prenom_admin;
        $this->nom_admin = $nom_admin;
        $this->email_admin = $email_admin;
    }
}
