<?php
// Vérifie que l'admin est connecté, redirige vers la page de login sinon
if (!isset($_SESSION['admin_id'])) {
    header("Location: index_.php?page=login.php");
    exit();
}
