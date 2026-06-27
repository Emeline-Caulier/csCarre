<?php

declare(strict_types=1);

// Accès aux données de la table message_contact via la vue v_messages_contact
class MessageDAO
{
    private PDO $_cnx;

    public function __construct(PDO $cnx)
    {
        $this->_cnx = $cnx;
    }

    // Retourne tous les messages (lus et non lus) avec leurs données complètes
    public function getAllMessages(): array
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT * FROM v_messages_contact");
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function changerStatut(int $id, string $statut): bool
    {
        try {
            $stmt = $this->_cnx->prepare("SELECT update_statut_message(:id, :statut) AS retour");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':statut', $statut);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // --- Écriture ---

    public function sendMessage(?int $idClient, string $nomContact, string $emailContact,
                                ?int $numCommande, string $sujet, string $contenu): bool
    {
        try {
            $stmt = $this->_cnx->prepare(
                "SELECT ajout_message(:id, :nom, :email, :num, :sujet, :contenu) AS retour"
            );
            $stmt->bindValue(':id', $idClient, $idClient === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':nom', $nomContact);
            $stmt->bindValue(':email', $emailContact);
            $stmt->bindValue(':num', $numCommande, $numCommande === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':sujet', $sujet);
            $stmt->bindValue(':contenu', $contenu);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // --- Statistiques dashboard ---

    public function countNonLus(): int
    {
        try {
            return (int)$this->_cnx->query("SELECT COUNT(*) FROM message_contact WHERE statut = 'non_lu'")->fetchColumn();
        } catch (PDOException $e) { error_log($e->getMessage()); return 0; }
    }

    // Hydrate depuis v_messages_contact (vue complète)
    private function hydrate(array $d): Message
    {
        return new Message(
            id_message: (int)$d['id_message'],
            id_client: $d['id_client'] !== null ? (int)$d['id_client'] : null,
            nom_contact: $d['nom_contact'],
            email_contact: $d['email_contact'],
            num_commande: $d['num_commande'] !== null ? (int)$d['num_commande'] : null,
            sujet: $d['sujet'],
            contenu: $d['contenu'],
            photo: $d['photo'],
            date_message: $d['date_message'],
            statut: $d['statut'],
            nom_client: $d['nom_client'],
            prenom_client: $d['prenom_client'],
            id_commande: $d['id_commande'] !== null ? (int)$d['id_commande'] : null,
        );
    }

}
