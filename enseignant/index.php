<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

// Statistiques
$total_salles = $conn->query("SELECT COUNT(*) as n FROM salles WHERE disponible=1")->fetch_assoc()['n'];
$mes_reservations = $conn->query("SELECT COUNT(*) as n FROM reservations WHERE user_id={$_SESSION['user_id']} AND statut='confirmée'")->fetch_assoc()['n'];
$reservations_auj = $conn->query("SELECT COUNT(*) as n FROM reservations WHERE user_id={$_SESSION['user_id']} AND date_reservation=CURDATE()")->fetch_assoc()['n'];

// Prochaines réservations
$stmt = $conn->prepare("SELECT r.*, s.nom as salle_nom, s.type as salle_type
    FROM reservations r
    JOIN salles s ON r.salle_id = s.id
    WHERE r.user_id = ? AND r.date_reservation >= CURDATE() AND r.statut='confirmée'
    ORDER BY r.date_reservation ASC, r.heure_debut ASC LIMIT 5");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$prochaines = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord — Enseignant</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_ens.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>Bonjour, <?= htmlspecialchars($_SESSION['prenom']) ?> 👋</h1>
                <p>Bienvenue sur votre espace de réservation</p>
            </div>
        </div>

        <div class="page-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">🏛️</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_salles ?></div>
                        <div class="stat-label">Salles disponibles</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">📅</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $mes_reservations ?></div>
                        <div class="stat-label">Mes réservations</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon yellow">⏰</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $reservations_auj ?></div>
                        <div class="stat-label">Aujourd'hui</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>📋 Mes prochaines réservations</h3>
                    <a href="../enseignant/reserver.php" class="btn btn-primary btn-sm">+ Nouvelle réservation</a>
                </div>
                <?php if ($prochaines->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Salle</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Horaire</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($r = $prochaines->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($r['salle_nom']) ?></strong></td>
                            <td><span class="badge badge-primary"><?= $r['salle_type'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($r['date_reservation'])) ?></td>
                            <td><?= substr($r['heure_debut'],0,5) ?> → <?= substr($r['heure_fin'],0,5) ?></td>
                            <td><span class="badge badge-success"><?= $r['statut'] ?></span></td>
                            <td>
                                <a href="/enseignant/annuler.php?id=<?= $r['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   data-confirm="Annuler cette réservation ?">Annuler</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>Aucune réservation à venir. <a href="../enseignant/reserver.php">Réserver une salle</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
