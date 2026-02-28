
<?php


require 'connexion.php';


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Médecine UCAD</title>
    <link href="style.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: sans-serif;
        }

        .container {
            width: 100%;
            max-width: 580px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            font-weight: 300;
        }

        /* Card glassmorphism */
        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

       
        .section-title {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 16px;
            margin-top: 24px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        /* Form group */
        .form-group {
            margin-bottom: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 6px;
            font-weight: 400;
        }

        input {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 11px 14px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
        }

        input::placeholder {
            color: rgba(255,255,255,0.2);
        }

        input:focus {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
        }

        hr {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 24px 0 20px;
        }

        /* Button */
        .btn {
            width: 100%;
            margin-top: 24px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 10px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255,255,255,0.4);
        }

        .bottom-link {
            text-align: center;
            margin-top: 16px;
        }

        .bottom-link a {
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .bottom-link a:hover {
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1> Admission Médecine</h1>
        <p>Université Cheikh Anta Diop — Dossier de candidature</p>
    </div>

    <div class="card">
        <form action="traitement.php" method="POST">

            <div class="section-title">Informations personnelles</div>

            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" placeholder="Ex : Gueye" required>
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" placeholder="Ex : Aicha" required>
            </div>

            <div class="form-group">
                <label>Âge</label>
                <input type="number" name="age" placeholder="Ex : 19" required min="1">
            </div>

            <hr>

            <div class="section-title">Notes &amp; Coefficients</div>

            <div class="form-row">
                <div>
                    <label>Note Mathématiques </label>
                    <input type="number" step="0.01" name="note_math" placeholder="Ex : 17.5" required min="0" max="20">
                </div>
                <div>
                    <label>Coefficient Math</label>
                    <input type="number" name="coef_math" placeholder="Ex : 3" required min="1">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Note Physique-Chimie</label>
                    <input type="number" step="0.01" name="note_pc" placeholder="Ex : 13" required min="0" max="20">
                </div>
                <div>
                    <label>Coefficient PC</label>
                    <input type="number" name="coef_pc" placeholder="Ex : 2" required min="1">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Note SVT</label>
                    <input type="number" step="0.01" name="note_svt" placeholder="Ex : 15" required min="0" max="20">
                </div>
                <div>
                    <label>Coefficient SVT</label>
                    <input type="number" name="coef_svt" placeholder="Ex : 2" required min="1">
                </div>
            </div>

            <button type="submit" class="btn">Vérifier l'admissibilité →</button>

        </form>
    </div>

    <div class="bottom-link">
        <a href="liste_etudiant.php">→ Consulter la liste des étudiants</a>
    </div>

</div>

</body>
</html>

