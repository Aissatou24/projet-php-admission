# 🏥 Système d'Admission — Faculté de Médecine UCAD

Application web PHP/MySQL de vérification d'admissibilité à la Faculté de Médecine
de l'Université Cheikh Anta Diop.

---

## 📁 Structure des fichiers

```
admission_medecine/
├── connexion.php          ← Connexion PDO à MySQL
├── index.php              ← Formulaire + traitement + résultat
├── liste_etudiants.php    ← Liste, recherche, filtres, export CSV
├── database.sql           ← Script SQL (base + table + données test)
└── README.md              ← Ce fichier
```

---

## ⚙️ Installation

### 1. Prérequis
- PHP 7.4+ avec extension PDO et PDO_MySQL
- MySQL 5.7+ ou MariaDB 10+
- Serveur web : Apache / Nginx / XAMPP / WAMP / Laragon

### 2. Configuration de la base de données

**Option A — Via phpMyAdmin :**
1. Ouvrez phpMyAdmin → Importez `database.sql`

**Option B — Via terminal MySQL :**
```bash
mysql -u root -p < database.sql
```

La base `admission_medecine` et la table `etudiants` seront créées automatiquement.  
> ℹ️ L'application crée aussi la base automatiquement au premier chargement si elle n'existe pas.

### 3. Configurer la connexion

Ouvrez `connexion.php` et adaptez :
```php
define('DB_HOST', 'localhost');   // Hôte MySQL
define('DB_NAME', 'admission_medecine');
define('DB_USER', 'root');        // ← Votre utilisateur MySQL
define('DB_PASS', '');            // ← Votre mot de passe MySQL
```

### 4. Déploiement

Copiez le dossier dans le répertoire de votre serveur :
- **XAMPP** : `C:/xampp/htdocs/admission_medecine/`
- **WAMP**  : `C:/wamp64/www/admission_medecine/`
- **Linux** : `/var/www/html/admission_medecine/`

Accédez ensuite à : `http://localhost/admission_medecine/`

---

## 🖥️ Pages de l'application

| Page | URL | Description |
|------|-----|-------------|
| Formulaire | `index.php` | Saisie et évaluation de l'étudiant |
| Liste | `liste_etudiants.php` | Tableau de tous les étudiants |

---

## 📐 Formule de calcul

```
Moyenne = (Note_Math × Coef_Math + Note_PC × Coef_PC + Note_SVT × Coef_SVT)
          ─────────────────────────────────────────────────────────────────────
                        (Coef_Math + Coef_PC + Coef_SVT)
```

**Critères d'admissibilité :**
- ✅ Moyenne **strictement supérieure** à 12/20
- ✅ Âge **strictement inférieur** à 22 ans

---

## ✨ Fonctionnalités

- Formulaire de saisie avec validation côté serveur
- Calcul automatique de la moyenne pondérée
- Affichage du résultat d'admissibilité personnalisé
- Liste paginable avec recherche par nom/prénom
- Filtres : Tous / Admissibles / Non admissibles
- Tri par colonne (nom, âge, moyenne, date)
- Export CSV
- Impression de la liste
- Statistiques globales (total, taux d'admission, moyennes)
- Création automatique de la base si elle n'existe pas