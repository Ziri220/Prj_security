<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// ⚠️ VULNÉRABILITÉ : Broken Access Control — pas de vérification que c'est sa propre réservation
$conn->query("UPDATE reservations SET statut='annulée' WHERE id=$id");

header("Location: mes_reservations.php");
exit();
?>
