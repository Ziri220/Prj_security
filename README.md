# 🏫 Gestion des Salles — Faculté
Application web PHP/MySQL pour la réservation de salles de cours.

---

## ⚙️ Installation (XAMPP / WAMP)

1. Copier le dossier `gestion_salles` dans `htdocs/`
2. Ouvrir phpMyAdmin → Importer `database.sql`
3. Modifier `includes/db.php` si nécessaire (DB_USER, DB_PASS)
4. Accéder à : `http://localhost/gestion_salles/login.php`

---

## 👤 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@faculte.ma | admin123 |
| Enseignant | alaoui@faculte.ma | prof123 |
| Enseignant | benali@faculte.ma | prof123 |

---

## 📁 Structure

```
gestion_salles/
├── database.sql
├── login.php
├── register.php
├── logout.php
├── css/style.css
├── js/main.js
├── includes/
│   ├── db.php
│   ├── auth.php
│   ├── sidebar_ens.php
│   └── sidebar_admin.php
├── enseignant/
│   ├── index.php
│   ├── reserver.php
│   ├── mes_reservations.php
│   └── annuler.php
└── admin/
    ├── index.php
    ├── salles.php
    ├── users.php
    └── reservations.php
```

---

## ⚠️ Vulnérabilités (Version initiale — pour audit)

| # | Vulnérabilité | Fichier | Description |
|---|--------------|---------|-------------|
| 1 | **SQL Injection** | `login.php` | Requête SQL non sécurisée |
| 2 | **SQL Injection** | `register.php` | Insertion sans préparation |
| 3 | **XSS** | `mes_reservations.php` | Motif affiché sans `htmlspecialchars` |
| 4 | **Stored XSS** | `reserver.php` | Motif sauvegardé sans filtrage |
| 5 | **Sensitive Data Exposure** | `admin/users.php` | Mots de passe en clair |
| 6 | **Broken Access Control** | `enseignant/annuler.php` | Pas de vérification de propriété |
| 7 | **No Password Hashing** | `register.php`, `login.php` | Passwords stockés en clair |

---

## 🛡️ Remédiation (à appliquer pour la version sécurisée)

- SQL Injection → Utiliser `PDO` avec requêtes préparées
- XSS → `htmlspecialchars()` sur tous les outputs
- Password → `password_hash()` + `password_verify()`
- Access Control → Vérifier `user_id` dans les requêtes de modification
- TLS → Déployer avec certificat SSL sur Render/Railway
