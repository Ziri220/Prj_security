<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE reservations SET statut='annulée' WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: mes_reservations.php");
exit();
