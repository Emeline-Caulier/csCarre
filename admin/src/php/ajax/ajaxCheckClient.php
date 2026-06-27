<?php
// Vérifie les identifiants d'un client et retourne ses infos.
// Réponse JSON :
//   - objet Client (sans mot_de_passe) si les identifiants sont valides
//   - { exists: true } si l'email existe mais le mdp ne correspond pas
//   - null si email introuvable
// Permet au front de basculer entre mode "connexion" et mode "inscription".
header('Content-Type: application/json');
require('../utils/all_includes.php');

$clientDAO = new ClientDAO($cnx);

$email = $_POST['email'] ?? '';
$password = $_POST['mot_de_passe'] ?? '';

$cl = $clientDAO->getClientByEmail($email);

if (!$cl) {
    echo json_encode(null);
    exit;
}

if (password_verify($password, $cl->mot_de_passe)) {
    unset($cl->mot_de_passe);
    echo json_encode($cl);
} else {
    // Email connu mais mot de passe incorrect : on signale uniquement l'existence
    // du compte, sans pré-remplir les champs adresse pour ne rien divulguer.
    echo json_encode(['exists' => true]);
}
