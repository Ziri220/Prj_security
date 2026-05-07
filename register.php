<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin/index.php' : 'enseignant/index.php'));
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = htmlspecialchars(strip_tags(trim($_POST['nom'])));
    $prenom = htmlspecialchars(strip_tags(trim($_POST['prenom'])));
    $email  = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, 'enseignant')");
        $stmt->bind_param("ssss", $nom, $prenom, $email, $hashed);

        if ($stmt->execute()) {
            $success = "Compte créé avec succès ! Vous pouvez vous connecter.";
        } else {
            $error = "Cet email est déjà utilisé.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — Gestion des Salles</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="icon">🏫</span>
            <h1>Créer un compte</h1>
            <p>Espace enseignant</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Alaoui" required>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Mohammed" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="prof@faculte.ma" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer mon compte</button>
        </form>

        <div class="auth-footer">
            Déjà un compte ? <a href="/login.php">Se connecter</a>
        </div>
    </div>
</div>
<script src="js/main.js"></script>
</body>
</html>
