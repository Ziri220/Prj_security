<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn() { return isset($_SESSION['user_id']); }
function isAdmin() { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function isEnseignant() { return isset($_SESSION['role']) && $_SESSION['role'] === 'enseignant'; }

function requireLogin() {
    if (!isLoggedIn()) { header("Location: ../login.php"); exit(); }
}
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) { header("Location: ../enseignant/index.php"); exit(); }
}
function requireEnseignant() {
    requireLogin();
    if (!isEnseignant() && !isAdmin()) { header("Location: ../login.php"); exit(); }
}
?>
