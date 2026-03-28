<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin/index.php' : 'enseignant/index.php'));
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ⚠️ VULNÉRABILITÉ : SQL Injection possible ici (intentionnel pour le projet)
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: enseignant/index.php');
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Gestion des Salles</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="icon">🏫</span>
            <h1>Gestion des Salles</h1>
            <p>Faculté — Système de réservation</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Adresse email</label>
                <input type="text" name="email" placeholder="votre@email.ma" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <div class="auth-footer">
            Pas encore de compte ? <a href="/register.php">S'inscrire</a>
        </div>
    </div>
</div>
<script src="js/main.js"></script>
</body>
</html>
