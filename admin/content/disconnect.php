<?php
// Déconnexion : vide la session puis redirige vers l'accueil client
$_SESSION = [];
session_destroy();
header("Location: ../index_.php");
exit;
