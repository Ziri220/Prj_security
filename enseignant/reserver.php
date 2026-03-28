<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salle_id = (int)$_POST['salle_id'];
    $date = $_POST['date_reservation'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $motif = $_POST['motif'];
    $user_id = $_SESSION['user_id'];

    if ($salle_id <= 0) {
        $error = "⚠️ Veuillez sélectionner une salle.";
    } elseif ($heure_fin <= $heure_debut) {
        $error = "⚠️ L'heure de fin doit être après l'heure de début.";
    } else {
        // Vérifier le conflit de créneau
        $check = $conn->query("
            SELECT id FROM reservations
            WHERE salle_id = $salle_id
            AND date_reservation = '$date'
            AND statut = 'confirmée'
            AND heure_debut < '$heure_fin'
            AND heure_fin > '$heure_debut'
        ");

        if ($check && $check->num_rows > 0) {
            $error = "⚠️ Cette salle est déjà réservée pour ce créneau. Choisissez un autre horaire ou une autre salle.";
        } else {
            // ⚠️ VULNÉRABILITÉ XSS : motif non échappé lors de l'affichage (intentionnel)
            $sql = "INSERT INTO reservations (user_id, salle_id, date_reservation, heure_debut, heure_fin, motif)
                    VALUES ($user_id, $salle_id, '$date', '$heure_debut', '$heure_fin', '$motif')";
            if ($conn->query($sql)) {
                $success = "✅ Réservation effectuée avec succès !";
            } else {
                $error = "Erreur lors de la réservation : " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver une salle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_ens.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>📅 Réserver une salle</h1>
                <p>Choisissez une salle et un créneau disponible</p>
            </div>
        </div>
        <div class="page-content">

            <!-- Salles disponibles -->
            <div class="card" style="margin-bottom:24px;">
                <div class="card-header"><h3>🏛️ Salles disponibles</h3></div>
                <div class="card-body">
                    <div class="salles-grid">
                    <?php
                    $salles_all = $conn->query("SELECT * FROM salles WHERE disponible=1 ORDER BY type, nom");
                    while ($s = $salles_all->fetch_assoc()):
                    ?>
                        <div class="salle-card free" onclick="selectSalle(<?= $s['id'] ?>, '<?= htmlspecialchars($s['nom']) ?>', this)">
                            <div class="salle-card-header">
                                <div>
                                    <div class="salle-name"><?= htmlspecialchars($s['nom']) ?></div>
                                    <div class="salle-type"><?= $s['type'] ?></div>
                                </div>
                                <span class="badge badge-success">Libre</span>
                            </div>
                            <div class="salle-info">
                                <span>👥 Capacité : <?= $s['capacite'] ?> personnes</span>
                                <span>🔧 <?= htmlspecialchars($s['equipements']) ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- Formulaire de réservation -->
            <div class="form-card">
                <h3 style="margin-bottom:20px;font-size:17px;">📝 Formulaire de réservation</h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Salle sélectionnée</label>
                        <select name="salle_id" id="salle_select" required>
                            <option value="">-- Cliquez sur une salle ci-dessus --</option>
                            <?php
                            $salles2 = $conn->query("SELECT * FROM salles WHERE disponible=1 ORDER BY type, nom");
                            while ($s = $salles2->fetch_assoc()):
                            ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nom']) ?> (<?= $s['type'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date de réservation</label>
                        <input type="date" name="date_reservation" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Heure de début</label>
                            <input type="time" name="heure_debut" required>
                        </div>
                        <div class="form-group">
                            <label>Heure de fin</label>
                            <input type="time" name="heure_fin" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Motif / Cours</label>
                        <input type="text" name="motif" placeholder="ex: Cours de Mathématiques S3">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                        <a href="../enseignant/index.php" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/main.js"></script>
<script>
function selectSalle(id, nom, el) {
    document.getElementById('salle_select').value = id;
    document.querySelectorAll('.salle-card').forEach(c => c.style.outline = 'none');
    el.style.outline = '3px solid #1a56db';
}
</script>
</body>
</html>