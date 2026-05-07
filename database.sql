-- =============================================
-- Base de données : Gestion des Salles - Faculté
-- =============================================

CREATE DATABASE IF NOT EXISTS gestionsalles CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestionsalles;

-- ---------------------------------------------
-- Table : users (enseignants + admin)
-- ---------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant') DEFAULT 'enseignant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Table : salles
-- ---------------------------------------------
CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    type ENUM('TD', 'TP', 'Amphi', 'Salle de réunion') NOT NULL,
    equipements TEXT,
    disponible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Table : reservations
-- ---------------------------------------------
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    salle_id INT NOT NULL,
    date_reservation DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    motif VARCHAR(255),
    statut ENUM('confirmée', 'annulée') DEFAULT 'confirmée',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE CASCADE
);

-- ---------------------------------------------
-- Données initiales (mots de passe hashés avec bcrypt)
-- admin123 => $2y$10$roas4xJObcamZx9TEQODuOUsTkMpAqYJM1taVUoeq5WjgoQieakBy
-- prof123  => $2y$10$VQde.r18wmpMU4yJ.hHXKOq63IE.HskBDBUrwY1BZ3JbfVpxw9qAu
-- ---------------------------------------------
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Admin', 'Système', 'admin@faculte.ma', '$2y$10$roas4xJObcamZx9TEQODuOUsTkMpAqYJM1taVUoeq5WjgoQieakBy', 'admin'),
('Alaoui', 'Mohammed', 'alaoui@faculte.ma', '$2y$10$VQde.r18wmpMU4yJ.hHXKOq63IE.HskBDBUrwY1BZ3JbfVpxw9qAu', 'enseignant'),
('Benali', 'Fatima', 'benali@faculte.ma', '$2y$10$VQde.r18wmpMU4yJ.hHXKOq63IE.HskBDBUrwY1BZ3JbfVpxw9qAu', 'enseignant');

INSERT INTO salles (nom, capacite, type, equipements) VALUES
('Salle A1', 30, 'TD', 'Tableau blanc, Projecteur'),
('Salle A2', 30, 'TD', 'Tableau blanc'),
('Salle B1', 50, 'TP', 'Ordinateurs x25, Projecteur'),
('Salle B2', 50, 'TP', 'Ordinateurs x25'),
('Amphi 1', 200, 'Amphi', 'Micro, Projecteur, Climatisation'),
('Amphi 2', 150, 'Amphi', 'Micro, Projecteur'),
('Salle Réunion', 20, 'Salle de réunion', 'TV écran, Tableau');
