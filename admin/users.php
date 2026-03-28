<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';

// Supprimer
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='enseignant'");
    $success = "Enseignant supprimé.";
}

$users = $conn->query("SELECT * FROM users WHERE role='enseignant' ORDER BY nom");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Enseignants</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>👥 Gestion des Enseignants</h1>
                <p>Liste de tous les enseignants inscrits</p>
            </div>
        </div>
        <div class="page-content">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Mot de passe</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <!-- ⚠️ VULNÉRABILITÉ : Affichage mot de passe en clair ! -->
                            <td><code style="background:#fde8e8;padding:2px 6px;border-radius:4px;"><?= $u['password'] ?></code></td>
                            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm"
                                   data-confirm="Supprimer cet enseignant ?">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
