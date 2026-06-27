<?php

declare(strict_types=1);

class Message
{
    public function __construct(
        public readonly int $id_message,
        public readonly ?int $id_client,
        public readonly string $nom_contact,
        public readonly string $email_contact,
        public readonly ?int $num_commande,
        public readonly string $sujet,
        public readonly string $contenu,
        public readonly ?string $photo,
        public readonly string $date_message,
        public readonly string $statut,
        public readonly ?string $nom_client,
        public readonly ?string $prenom_client,
        public readonly ?int $id_commande,
    ) {}
}
