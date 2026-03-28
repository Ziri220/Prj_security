<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';

// Annuler
if (isset($_GET['cancel'])) {
    $id = (int)$_GET['cancel'];
    $conn->query("UPDATE reservations SET statut='annulée' WHERE id=$id");
    $success = "Réservation annulée.";
}

// Filtrer par date
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$where = $filter_date ? "WHERE r.date_reservation='$filter_date'" : "";

$reservations = $conn->query("SELECT r.*, u.nom, u.prenom, s.nom as salle_nom, s.type as salle_type
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN salles s ON r.salle_id = s.id
    $where
    ORDER BY r.date_reservation DESC, r.heure_debut ASC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Réservations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>📅 Toutes les Réservations</h1>
                <p>Gérer et suivre toutes les réservations</p>
            </div>
        </div>
        <div class="page-content">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Filtre -->
            <div class="card" style="margin-bottom:20px;">
                <div class="card-body">
                    <form method="GET" style="display:flex;gap:12px;align-items:center;">
                        <label style="font-size:14px;font-weight:500;">Filtrer par date :</label>
                        <input type="date" name="date" value="<?= $filter_date ?>" style="padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:inherit;">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                        <?php if ($filter_date): ?>
                            <a href="/admin/reservations.php" class="btn btn-secondary btn-sm">Effacer</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card">
                <?php if ($reservations->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Enseignant</th>
                            <th>Salle</th>
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
                            <td><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($r['salle_nom']) ?></strong><br>
                                <span style="font-size:11px;color:#6b7280;"><?= $r['salle_type'] ?></span>
                            </td>
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
                                    <a href="?cancel=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
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
                    <p>Aucune réservation trouvée.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
