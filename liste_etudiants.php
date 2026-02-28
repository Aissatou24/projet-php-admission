<?php
require "connexion.php";

$sql = "SELECT * FROM etudiants ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des étudiants</title>
</head>
<body>

<h2>Liste des étudiants enregistrés</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Âge</th>
    <th>Moyenne</th>
    <th>Statut</th>
</tr>

<?php foreach($etudiants as $etudiant): ?>
<tr>
    <td><?= $etudiant['nom'] ?></td>
    <td><?= $etudiant['prenom'] ?></td>
    <td><?= $etudiant['age'] ?></td>
    <td><?= number_format($etudiant['moyenne'], 2) ?></td>
    <td><?= $etudiant['admissible'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<br>
<!-- Bouton Retour -->
    <a href="index.php">
        <button class="btn">Retour au formulaire</button>
    </a>

</body>
</html>