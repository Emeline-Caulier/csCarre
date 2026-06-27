<?php

declare(strict_types=1);

// Représente une commande client, issue de la vue v_commandes_client
class Commande
{
    public function __construct(
        public readonly int $id_commande,
        public readonly string $date_commande,
        public readonly int $id_client,
        public readonly string $nom_client,
        public readonly string $prenom_client,
        public readonly string $email_client,
        public readonly string $total_commande,
        public readonly bool $statut_paiement,
        public readonly string $statut_commande,
        public readonly string $ville,
        public readonly string $pays
    ) {}
}
