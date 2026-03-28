<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$total_salles = $conn->query("SELECT COUNT(*) as n FROM salles")->fetch_assoc()['n'];
$total_users = $conn->query("SELECT COUNT(*) as n FROM users WHERE role='enseignant'")->fetch_assoc()['n'];
$total_reservations = $conn->query("SELECT COUNT(*) as n FROM reservations WHERE statut='confirmée'")->fetch_assoc()['n'];
$reservations_auj = $conn->query("SELECT COUNT(*) as n FROM reservations WHERE date_reservation=CURDATE() AND statut='confirmée'")->fetch_assoc()['n'];

$recent = $conn->query("SELECT r.*, u.nom, u.prenom, s.nom as salle_nom
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN salles s ON r.salle_id = s.id
    ORDER BY r.created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Tableau de bord</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>📊 Tableau de bord Admin</h1>
                <p>Vue d'ensemble du système</p>
            </div>
        </div>
        <div class="page-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">🏛️</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_salles ?></div>
                        <div class="stat-label">Total salles</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">👥</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_users ?></div>
                        <div class="stat-label">Enseignants</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon yellow">📅</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_reservations ?></div>
                        <div class="stat-label">Réservations actives</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">⏰</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $reservations_auj ?></div>
                        <div class="stat-label">Aujourd'hui</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>🕒 Réservations récentes</h3>
                    <a href="/admin/reservations.php" class="btn btn-secondary btn-sm">Voir tout</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Enseignant</th>
                            <th>Salle</th>
                            <th>Date</th>
                            <th>Horaire</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($r = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></td>
                            <td><?= htmlspecialchars($r['salle_nom']) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['date_reservation'])) ?></td>
                            <td><?= substr($r['heure_debut'],0,5) ?> → <?= substr($r['heure_fin'],0,5) ?></td>
                            <td>
                                <?php if ($r['statut'] === 'confirmée'): ?>
                                    <span class="badge badge-success">✅ Confirmée</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">❌ Annulée</span>
                                <?php endif; ?>
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
