<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🏫</div>
        <h2>Gestion des Salles</h2>
        <p>Administration</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Admin</div>
        <a href="../admin/index.php" class="nav-item">
            <span class="nav-icon">📊</span> Tableau de bord
        </a>
        <a href="../admin/salles.php" class="nav-item">
            <span class="nav-icon">🏛️</span> Gestion des salles
        </a>
        <a href="../admin/users.php" class="nav-item">
            <span class="nav-icon">👥</span> Enseignants
        </a>
        <a href="../admin/reservations.php" class="nav-item">
            <span class="nav-icon">📅</span> Réservations
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar" style="background:#e02424;">A</div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></div>
            <div class="user-role">Administrateur</div>
        </div>
        <a href="../logout.php" class="logout-btn" title="Déconnexion">🚪</a>
    </div>
</div>
