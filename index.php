<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admission Médecine - UCAD</title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="container">
    <h2>Admission Faculté de Médecine<br>
        Université Cheikh Anta Diop
    </h2>

    <form action="traitement.php" method="POST">

        <div class="row">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>
        </div>

        <div class="form-group">
            <label>Âge</label>
            <input type="number" name="age" required>
        </div>

        <div class="row">
            <div class="form-group">
                <label>Note Mathématiques</label>
                <input type="number" step="0.01" name="note_math" required>
            </div>

            <div class="form-group">
                <label>Coefficient Math</label>
                <input type="number" name="coef_math" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <label>Note Physique-Chimie</label>
                <input type="number" step="0.01" name="note_pc" required>
            </div>

            <div class="form-group">
                <label>Coefficient PC</label>
                <input type="number" name="coef_pc" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <label>Note SVT</label>
                <input type="number" step="0.01" name="note_svt" required>
            </div>

            <div class="form-group">
                <label>Coefficient SVT</label>
                <input type="number" name="coef_svt" required>
            </div>
        </div>

        <button type="submit" class="btn">Vérifier l'admission</button>

    </form>

    <div class="footer">
        Projet Admission Médecine - UCAD
    </div>
</div>

</body>
</html>