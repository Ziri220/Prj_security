<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🏫</div>
        <h2>Gestion des Salles</h2>
        <p>Faculté</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Menu</div>
        <a href="../enseignant/index.php" class="nav-item">
            <span class="nav-icon">🏠</span> Tableau de bord
        </a>
        <a href="../enseignant/reserver.php" class="nav-item">
            <span class="nav-icon">📅</span> Réserver une salle
        </a>
        <a href="../enseignant/mes_reservations.php" class="nav-item">
            <span class="nav-icon">📋</span> Mes réservations
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?= strtoupper(substr($_SESSION['prenom'], 0, 1) . substr($_SESSION['nom'], 0, 1)) ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></div>
            <div class="user-role">Enseignant</div>
        </div>
        <a href="../logout.php" class="logout-btn" title="Déconnexion">🚪</a>
    </div>
</div>
