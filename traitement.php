<?php
require "connexion.php";

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$age = $_POST['age'];

$note_math = $_POST['note_math'];
$coef_math = $_POST['coef_math'];

$note_pc = $_POST['note_pc'];
$coef_pc = $_POST['coef_pc'];

$note_svt = $_POST['note_svt'];
$coef_svt = $_POST['coef_svt'];

$total_coef = $coef_math + $coef_pc + $coef_svt;

$moyenne = (
    ($note_math * $coef_math) +
    ($note_pc * $coef_pc) +
    ($note_svt * $coef_svt)
) / $total_coef;

if ($moyenne > 12 && $age < 22) {
    $admissible = "Oui";
    $message = "Félicitations $nom $prenom, vous êtes admissible pour rejoindre la faculté de médecine de l’Université Cheikh Anta Diop.";
    $icon = "✅";
} else {
    $admissible = "Non";
    $message = "Désolé $nom $prenom, vous n’êtes pas admissible à la faculté de médecine.";
    $icon = "❌";
}

$sql = "INSERT INTO etudiants 
(nom, prenom, age, note_math, coef_math, note_pc, coef_pc, note_svt, coef_svt, moyenne, admissible)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $nom,
    $prenom,
    $age,
    $note_math,
    $coef_math,
    $note_pc,
    $coef_pc,
    $note_svt,
    $coef_svt,
    $moyenne,
    $admissible
]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Résultat Admission</title>
    <style>
        /* BODY */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00bcd4, #8bc34a);
            margin: 0;
            padding: 0;
        }

        /* Container */
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        /* Titre */
        .container h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 25px;
        }

        /* Message */
        .container p {
            font-size: 20px;
            color: #555;
            margin-bottom: 30px;
        }

        .container p span.icon {
            font-size: 32px;
            margin-right: 10px;
        }

        /* Boutons */
        .btn {
            background-color: #00bcd4;
            color: #fff;
            padding: 12px 30px;
            margin: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0097a7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Résultat Admission</h2>

    <p>
        <span class="icon"><?= $icon ?></span>
        <?= $message ?>
    </p>

    <!-- Bouton Liste -->
    <a href="liste_etudiant.php" class="btn">Voir la liste des étudiants</a>

    <!-- Bouton Retour -->
    <a href="index.php" class="btn">Retour au formulaire</a>
</div>

</body>
</html>