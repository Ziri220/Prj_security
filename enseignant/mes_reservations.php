<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT r.*, s.nom as salle_nom, s.type as salle_type
    FROM reservations r
    JOIN salles s ON r.salle_id = s.id
    WHERE r.user_id = ?
    ORDER BY r.date_reservation DESC, r.heure_debut DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Réservations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_ens.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>📋 Mes Réservations</h1>
                <p>Historique de toutes vos réservations</p>
            </div>
            <a href="/enseignant/reserver.php" class="btn btn-primary">+ Nouvelle réservation</a>
        </div>
        <div class="page-content">
            <div class="card">
                <?php if ($reservations->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Salle</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Horaire</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($r = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><strong><?= htmlspecialchars($r['salle_nom']) ?></strong></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($r['salle_type']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($r['date_reservation'])) ?></td>
                            <td><?= substr($r['heure_debut'],0,5) ?> → <?= substr($r['heure_fin'],0,5) ?></td>
                            <td><?= htmlspecialchars($r['motif']) ?></td>
                            <td>
                                <?php if ($r['statut'] === 'confirmée'): ?>
                                    <span class="badge badge-success">✅ Confirmée</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">❌ Annulée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['statut'] === 'confirmée'): ?>
                                    <a href="/enseignant/annuler.php?id=<?= $r['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       data-confirm="Annuler cette réservation ?">Annuler</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>Aucune réservation trouvée. <a href="/enseignant/reserver.php">Faire une réservation</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
