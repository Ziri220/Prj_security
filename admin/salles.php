<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$error = '';
$success = '';

// Supprimer
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM salles WHERE id=$id");
    $success = "Salle supprimée.";
}

// Ajouter / Modifier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $capacite = (int)$_POST['capacite'];
    $type = $_POST['type'];
    $equipements = $_POST['equipements'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    if (isset($_POST['edit_id']) && $_POST['edit_id']) {
        $id = (int)$_POST['edit_id'];
        $conn->query("UPDATE salles SET nom='$nom', capacite=$capacite, type='$type', equipements='$equipements', disponible=$disponible WHERE id=$id");
        $success = "Salle mise à jour.";
    } else {
        $conn->query("INSERT INTO salles (nom, capacite, type, equipements, disponible) VALUES ('$nom', $capacite, '$type', '$equipements', $disponible)");
        $success = "Salle ajoutée.";
    }
}

$salles = $conn->query("SELECT * FROM salles ORDER BY type, nom");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Gestion des Salles</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div>
                <h1>🏛️ Gestion des Salles</h1>
                <p>Ajouter, modifier et supprimer des salles</p>
            </div>
            <button class="btn btn-primary" onclick="openModal('modalAjout')">+ Ajouter une salle</button>
        </div>
        <div class="page-content">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Capacité</th>
                            <th>Équipements</th>
                            <th>Disponible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($s = $salles->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($s['nom']) ?></strong></td>
                            <td><span class="badge badge-primary"><?= $s['type'] ?></span></td>
                            <td>👥 <?= $s['capacite'] ?></td>
                            <td><?= htmlspecialchars($s['equipements']) ?></td>
                            <td>
                                <?php if ($s['disponible']): ?>
                                    <span class="badge badge-success">✅ Oui</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">❌ Non</span>
                                <?php endif; ?>
                            </td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn btn-warning btn-sm"
                                    onclick="editSalle(<?= $s['id'] ?>, '<?= addslashes($s['nom']) ?>', <?= $s['capacite'] ?>, '<?= $s['type'] ?>', '<?= addslashes($s['equipements']) ?>', <?= $s['disponible'] ?>)">
                                    ✏️ Modifier
                                </button>
                                <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm"
                                   data-confirm="Supprimer cette salle ?">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout/Modif -->
<div class="modal-overlay" id="modalAjout">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">➕ Ajouter une salle</h3>
            <button class="modal-close" onclick="closeModal('modalAjout')">✕</button>
        </div>
        <form method="POST">
            <input type="hidden" name="edit_id" id="edit_id" value="">
            <div class="form-group">
                <label>Nom de la salle</label>
                <input type="text" name="nom" id="f_nom" placeholder="ex: Salle A1" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Capacité</label>
                    <input type="number" name="capacite" id="f_cap" placeholder="30" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="f_type">
                        <option value="TD">TD</option>
                        <option value="TP">TP</option>
                        <option value="Amphi">Amphi</option>
                        <option value="Salle de réunion">Salle de réunion</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Équipements</label>
                <input type="text" name="equipements" id="f_equip" placeholder="Projecteur, Tableau blanc...">
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="disponible" id="f_dispo" value="1" checked>
                    Disponible
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalAjout')">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
function editSalle(id, nom, cap, type, equip, dispo) {
    document.getElementById('modal-title').textContent = '✏️ Modifier la salle';
    document.getElementById('edit_id').value = id;
    document.getElementById('f_nom').value = nom;
    document.getElementById('f_cap').value = cap;
    document.getElementById('f_type').value = type;
    document.getElementById('f_equip').value = equip;
    document.getElementById('f_dispo').checked = dispo == 1;
    openModal('modalAjout');
}
</script>
</body>
</html>
