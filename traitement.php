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
} else {
    $admissible = "Non";
    $message = "Désolé $nom $prenom, vous n’êtes pas admissible à la faculté de médecine.";
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
    <title>Résultat Admission</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Résultat</h2>

    <p style="text-align:center; font-size:18px; margin-bottom:20px;">
        <?= $message ?>
    </p>

    <!-- Bouton Liste -->
    <a href="liste_etudiants.php">
        <button class="btn">Voir la liste des étudiants</button>
    </a>

    <br><br>

    <!-- Bouton Retour -->
    <a href="index.php">
        <button class="btn">Retour au formulaire</button>
    </a>

</div>

</body>
</html>