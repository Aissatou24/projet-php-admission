<?php
/**
 * index.php - Formulaire de saisie et traitement d'admissibilité
 * Faculté de Médecine - Université Cheikh Anta Diop
 */

require_once 'connexion.php';

// ─── Création automatique de la base et de la table si elles n'existent pas ───
function initDatabase(): void {
    // Connexion sans base de données pour la création initiale
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `" . DB_NAME . "`");
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `etudiants` (
                `id`          INT           NOT NULL AUTO_INCREMENT,
                `nom`         VARCHAR(100)  NOT NULL,
                `prenom`      VARCHAR(100)  NOT NULL,
                `age`         INT           NOT NULL,
                `note_math`   FLOAT         NOT NULL,
                `coef_math`   INT           NOT NULL,
                `note_pc`     FLOAT         NOT NULL,
                `coef_pc`     INT           NOT NULL,
                `note_svt`    FLOAT         NOT NULL,
                `coef_svt`    INT           NOT NULL,
                `moyenne`     FLOAT         NOT NULL,
                `admissible`  VARCHAR(20)   NOT NULL,
                `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    } catch (PDOException $e) {
        die('<p style="color:red">Impossible d\'initialiser la base de données : ' . htmlspecialchars($e->getMessage()) . '</p>');
    }
}
initDatabase();

// ─── Traitement du formulaire ──────────────────────────────────────────────────
$message  = '';
$alertClass = '';
$etudiant = null;
$errors   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Récupération et nettoyage des données
    $nom       = trim($_POST['nom']       ?? '');
    $prenom    = trim($_POST['prenom']    ?? '');
    $age       = intval($_POST['age']     ?? 0);
    $note_math = floatval($_POST['note_math'] ?? 0);
    $coef_math = intval($_POST['coef_math']   ?? 1);
    $note_pc   = floatval($_POST['note_pc']   ?? 0);
    $coef_pc   = intval($_POST['coef_pc']     ?? 1);
    $note_svt  = floatval($_POST['note_svt']  ?? 0);
    $coef_svt  = intval($_POST['coef_svt']    ?? 1);

    // Validation
    if (empty($nom))    $errors[] = "Le nom est obligatoire.";
    if (empty($prenom)) $errors[] = "Le prénom est obligatoire.";
    if ($age <= 0 || $age > 100) $errors[] = "L'âge doit être compris entre 1 et 100 ans.";
    foreach (['note_math' => $note_math, 'note_pc' => $note_pc, 'note_svt' => $note_svt] as $label => $val) {
        if ($val < 0 || $val > 20) $errors[] = "La note $label doit être entre 0 et 20.";
    }
    foreach (['coef_math' => $coef_math, 'coef_pc' => $coef_pc, 'coef_svt' => $coef_svt] as $label => $val) {
        if ($val < 1) $errors[] = "Le coefficient $label doit être au moins 1.";
    }

    if (empty($errors)) {
        // 2. Calcul de la moyenne pondérée
        $total_coef = $coef_math + $coef_pc + $coef_svt;
        $moyenne = ($note_math * $coef_math + $note_pc * $coef_pc + $note_svt * $coef_svt) / $total_coef;
        $moyenne = round($moyenne, 2);

        // 3. Vérification des conditions d'admissibilité
        $admissible = ($moyenne > 12 && $age < 22) ? 'Oui' : 'Non';

        // 4. Enregistrement en base de données
        $pdo = getConnexion();
        $stmt = $pdo->prepare("
            INSERT INTO etudiants (nom, prenom, age, note_math, coef_math, note_pc, coef_pc, note_svt, coef_svt, moyenne, admissible)
            VALUES (:nom, :prenom, :age, :note_math, :coef_math, :note_pc, :coef_pc, :note_svt, :coef_svt, :moyenne, :admissible)
        ");
        $stmt->execute(compact('nom','prenom','age','note_math','coef_math','note_pc','coef_pc','note_svt','coef_svt','moyenne','admissible'));

        $etudiant = compact('nom','prenom','age','moyenne','admissible');

        // 5. Message final
        if ($admissible === 'Oui') {
            $message    = "🎉 Félicitations <strong>$prenom $nom</strong>, vous êtes admissible pour rejoindre la faculté de médecine de l'Université Cheikh Anta Diop.";
            $alertClass = 'success';
        } else {
            $message    = "😞 Désolé <strong>$prenom $nom</strong>, vous n'êtes pas admissible à la faculté de médecine.";
            $alertClass = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admission Faculté de Médecine – UCAD</title>
        <style>
            /* ── Reset & Base ── */
            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #1a2a6c, #b21f1f, #1a2a6c);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 30px 15px;
            }

            /* ── Header ── */
            header {
                text-align: center;
                color: #fff;
                margin-bottom: 30px;
            }

            header .badge {
                display: inline-block;
                background: rgba(255, 255, 255, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: #fff;
                font-size: 0.75rem;
                letter-spacing: 2px;
                text-transform: uppercase;
                padding: 4px 14px;
                border-radius: 20px;
                margin-bottom: 12px;
            }

            header h1 {
                font-size: clamp(1.4rem, 4vw, 2.2rem);
                font-weight: 700;
                line-height: 1.3;
            }

            header p {
                margin-top: 8px;
                font-size: 0.95rem;
                opacity: 0.85;
            }

            /* ── Card ── */
            .card {
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                width: 100%;
                max-width: 680px;
                overflow: hidden;
            }

            .card-header {
                background: linear-gradient(90deg, #1a2a6c, #b21f1f);
                color: #fff;
                padding: 20px 30px;
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .card-header .icon {
                font-size: 2rem;
            }

            .card-header h2 {
                font-size: 1.1rem;
                font-weight: 600;
            }

            .card-header span {
                font-size: 0.8rem;
                opacity: 0.8;
            }

            .card-body {
                padding: 30px;
            }

            /* ── Alert ── */
            .alert {
                border-radius: 10px;
                padding: 16px 20px;
                margin-bottom: 24px;
                font-size: 1rem;
                line-height: 1.5;
                border-left: 5px solid;
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }

            .alert-success {
                background: #d4edda;
                border-color: #28a745;
                color: #155724;
            }

            .alert-danger {
                background: #f8d7da;
                border-color: #dc3545;
                color: #721c24;
            }

            .alert-warning {
                background: #fff3cd;
                border-color: #ffc107;
                color: #856404;
            }

            .alert-icon {
                font-size: 1.4rem;
                flex-shrink: 0;
            }

            /* ── Result Box ── */
            .result-box {
                background: #f8f9ff;
                border: 1px solid #dee2e6;
                border-radius: 10px;
                padding: 16px 20px;
                margin-bottom: 24px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .result-item {
                display: flex;
                flex-direction: column;
            }

            .result-item .label {
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #6c757d;
            }

            .result-item .value {
                font-size: 1.05rem;
                font-weight: 600;
                color: #1a2a6c;
            }

            .badge-status {
                display: inline-block;
                padding: 2px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .badge-oui {
                background: #d4edda;
                color: #155724;
            }

            .badge-non {
                background: #f8d7da;
                color: #721c24;
            }

            /* ── Form ── */
            .section-title {
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: #6c757d;
                font-weight: 600;
                margin-bottom: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .section-title::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #dee2e6;
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 14px;
                margin-bottom: 14px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .form-group.full {
                grid-column: 1 / -1;
            }

            label {
                font-size: 0.82rem;
                font-weight: 600;
                color: #495057;
            }

            label .req {
                color: #dc3545;
                margin-left: 2px;
            }

            input[type="text"], input[type="number"] {
                border: 1px solid #ced4da;
                border-radius: 8px;
                padding: 9px 12px;
                font-size: 0.9rem;
                color: #212529;
                transition: border-color .2s, box-shadow .2s;
                width: 100%;
            }

            input:focus {
                outline: none;
                border-color: #1a2a6c;
                box-shadow: 0 0 0 3px rgba(26, 42, 108, 0.12);
            }

            .help-text {
                font-size: 0.72rem;
                color: #6c757d;
            }

            .matiere-block {
                background: #f8f9ff;
                border: 1px solid #e0e4f0;
                border-radius: 10px;
                padding: 16px;
                margin-bottom: 14px;
            }

            .matiere-block .matiere-title {
                font-size: 0.85rem;
                font-weight: 700;
                color: #1a2a6c;
                margin-bottom: 12px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            /* ── Errors ── */
            .error-list {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 10px;
                padding: 14px 18px;
                margin-bottom: 20px;
                color: #721c24;
            }

            .error-list ul {
                margin-left: 18px;
                margin-top: 6px;
            }

            .error-list li {
                margin-bottom: 4px;
                font-size: 0.88rem;
            }

            /* ── Buttons ── */
            .btn-row {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                margin-top: 22px;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 24px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-size: 0.92rem;
                font-weight: 600;
                text-decoration: none;
                transition: transform .15s, box-shadow .15s;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            }

            .btn-primary {
                background: linear-gradient(90deg, #1a2a6c, #b21f1f);
                color: #fff;
                flex: 1;
                justify-content: center;
            }

            .btn-secondary {
                background: #fff;
                color: #1a2a6c;
                border: 2px solid #1a2a6c;
            }

            .btn-list {
                background: #fff;
                color: #495057;
                border: 1px solid #ced4da;
            }

            /* ── Responsive ── */

            @media (max-width: 520px) {
                .form-row {
                    grid-template-columns: 1fr;
                }
                .result-box {
                    grid-template-columns: 1fr;
                }
            }

            footer {
                color: rgba(255, 255, 255, 0.6);
                font-size: 0.78rem;
                margin-top: 24px;
                text-align: center;
            }

        </style>
    </head>
    <body>
        <header>
            <div class="badge">🎓 Système d'Admission</div>
            <h1>Faculté de Médecine<br>Université Cheikh Anta Diop</h1>
            <p>Vérification automatique de l'admissibilité des étudiants</p>
        </header>
        <div class="card">
            <div class="card-header">
                <div class="icon">📋</div>
                <div>
                    <h2>Formulaire d'Inscription</h2>
                    <span>Remplissez tous les champs pour évaluer votre admissibilité</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <strong>⚠️ Veuillez corriger les erreurs suivantes :</strong>
                    <ul>
                        <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php if ($message): ?>
                <div class="alert alert-<?= $alertClass ?>">
                    <div class="alert-icon"><?= $alertClass === 'success' ? '🎉' : '😞' ?></div>
                    <div><?= $message ?></div>
                </div>
                <?php if ($etudiant): ?>
                <div class="result-box">
                    <div class="result-item">
                        <span class="label">Étudiant</span>
                        <span class="value"><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></span>
                    </div>
                    <div class="result-item">
                        <span class="label">Âge</span>
                        <span class="value"><?= $etudiant['age'] ?> ans</span>
                    </div>
                    <div class="result-item">
                        <span class="label">Moyenne Pondérée</span>
                        <span class="value"><?= number_format($etudiant['moyenne'], 2) ?> / 20</span>
                    </div>
                    <div class="result-item">
                        <span class="label">Statut</span>
                        <span class="value">
                            <span class="badge-status badge-<?= strtolower($etudiant['admissible']) ?>"> <?= $etudiant['admissible'] === 'Oui' ? '✅ Admissible' : '❌ Non admissible' ?> </span>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <!-- ── Formulaire ── -->
                <form method="POST" action="">
                    <div class="section-title">Informations personnelles</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom <span class="req">*</span></label>
                            <input type="text" id="nom" name="nom"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                           placeholder="Ex : Diallo" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom <span class="req">*</span></label>
                            <input type="text" id="prenom" name="prenom"
                           value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                           placeholder="Ex : Fatou" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Âge <span class="req">*</span></label>
                            <input type="number" id="age" name="age" min="1" max="100"
                           value="<?= intval($_POST['age'] ?? '') ?: '' ?>"
                           placeholder="Ex : 19" required>
                            <span class="help-text">Doit être &lt; 22 ans pour être admissible</span>
                        </div>
                    </div>
                    <div class="section-title" style="margin-top:10px;">Notes et coefficients</div>
                    <!-- Mathématiques -->
                    <div class="matiere-block">
                        <div class="matiere-title">📐 Mathématiques</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="note_math">Note <span class="req">*</span></label>
                                <input type="number" id="note_math" name="note_math" min="0" max="20" step="0.01"
                               value="<?= $_POST['note_math'] ?? '' ?>"
                               placeholder="0.00 – 20.00" required>
                            </div>
                            <div class="form-group">
                                <label for="coef_math">Coefficient <span class="req">*</span></label>
                                <input type="number" id="coef_math" name="coef_math" min="1" max="20"
                               value="<?= intval($_POST['coef_math'] ?? 3) ?: 3 ?>"
                               placeholder="Ex : 3" required>
                            </div>
                        </div>
                    </div>
                    <!-- Physique-Chimie -->
                    <div class="matiere-block">
                        <div class="matiere-title">⚗️ Physique-Chimie</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="note_pc">Note <span class="req">*</span></label>
                                <input type="number" id="note_pc" name="note_pc" min="0" max="20" step="0.01"
                               value="<?= $_POST['note_pc'] ?? '' ?>"
                               placeholder="0.00 – 20.00" required>
                            </div>
                            <div class="form-group">
                                <label for="coef_pc">Coefficient <span class="req">*</span></label>
                                <input type="number" id="coef_pc" name="coef_pc" min="1" max="20"
                               value="<?= intval($_POST['coef_pc'] ?? 2) ?: 2 ?>"
                               placeholder="Ex : 2" required>
                            </div>
                        </div>
                    </div>
                    <!-- SVT -->
                    <div class="matiere-block">
                        <div class="matiere-title">🧬 Sciences de la Vie et de la Terre (SVT)</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="note_svt">Note <span class="req">*</span></label>
                                <input type="number" id="note_svt" name="note_svt" min="0" max="20" step="0.01"
                               value="<?= $_POST['note_svt'] ?? '' ?>"
                               placeholder="0.00 – 20.00" required>
                            </div>
                            <div class="form-group">
                                <label for="coef_svt">Coefficient <span class="req">*</span></label>
                                <input type="number" id="coef_svt" name="coef_svt" min="1" max="20"
                               value="<?= intval($_POST['coef_svt'] ?? 3) ?: 3 ?>"
                               placeholder="Ex : 3" required>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning" style="margin-top:4px;">
                        <div class="alert-icon">ℹ️</div>
                        <div style="font-size:0.85rem;"> <strong>Critères d'admissibilité :</strong><br> • Moyenne pondérée <strong>strictement supérieure à 12/20</strong><br> • Âge <strong>strictement inférieur à 22 ans</strong> </div>
                    </div>
                    <div class="btn-row">
                        <button type="submit" class="btn btn-primary"> ✅ Vérifier mon admissibilité </button>
                        <button type="reset" class="btn btn-secondary">↺ Réinitialiser</button>
                    </div>
                    <div style="margin-top:16px; text-align:center;">
                        <a href="liste_etudiants.php" class="btn btn-list"> 📊 Voir la liste des étudiants </a>
                    </div>
                </form>
            </div>
        </div>
        <footer> &copy; <?= date('Y') ?> Université Cheikh Anta Diop – Système d'Admission Faculté de Médecine </footer>
    </body>
</html>
