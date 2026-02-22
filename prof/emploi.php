<?php
session_start();
if (!isset($_SESSION['prof_id'])) {
    header("Location: ../login/login_prof.php");
    exit();
}

require_once '../includes/db.php';

$prof_id = $_SESSION['prof_id'];

$stmt = $pdo->prepare("SELECT nom FROM professeurs WHERE id = ?");
$stmt->execute([$prof_id]);
$prof = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT * FROM enseignement WHERE prof_id = ? ORDER BY 
   FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), heure_debut");
$stmt2->execute([$prof_id]);
$cours = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Emploi du Temps</title>
    <link rel="stylesheet" href="../assets/css/emploi.css">
</head>
<body>

    <h2>📅 Emploi du temps de  <?= htmlspecialchars($prof['nom']) ?></h2>

    <?php if (count($cours) > 0): ?>
        <table>
            <tr>
                <th>Jour</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th>Classe</th>
                <th>Matière</th>
            </tr>
            <?php foreach ($cours as $ligne): ?>
                <tr>
                    <td><?= htmlspecialchars($ligne['jour']) ?></td>
                    <td><?= htmlspecialchars(substr($ligne['heure_debut'], 0, 5)) ?></td>
                    <td><?= htmlspecialchars(substr($ligne['heure_fin'], 0, 5)) ?></td>
                    <td><?= htmlspecialchars($ligne['classe']) ?></td>
                    <td><?= htmlspecialchars($ligne['matiere']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center; margin-top: 30px;">Aucun cours attribué pour l’instant.</p>
    <?php endif; ?>

    <a class="back" href="dashboard.php">⬅ Retour au tableau de bord</a>

</body>
</html>
