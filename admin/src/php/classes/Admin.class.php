<?php

declare(strict_types=1);

// Représente un administrateur de l'application
class Admin
{
    public function __construct(
        public readonly int $id_admin,
        public readonly string $prenom_admin,
        public readonly string $nom_admin,
        public readonly string $email_admin
    ) {}
}
