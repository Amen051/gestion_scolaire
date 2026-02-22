<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT * FROM professeurs ORDER BY nom");
$profs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Professeurs</title>
     <link rel="stylesheet" href="../assets/css/prof.css">
</head>
<body>

<header>
    <a href="dashboard.php" class="btn">⬅️ Retour à l'espace admin</a>
    <h1>Gestion des Professeurs</h1>
</header>

<div class="tabs">
    <a href="professeurs.php" class="active">👨‍🏫 Professeurs</a>
    <a href="enseignement.php">📚 Enseignement</a>
</div>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profs as $prof): ?>
            <tr>
                <td><?= htmlspecialchars($prof['nom']) ?></td>
                <td><?= htmlspecialchars($prof['email']) ?></td>
                <td><?= htmlspecialchars($prof['telephone']) ?></td>
                <td class="actions">
                    <a href="modifier_prof.php?id=<?= $prof['id'] ?>">✏️ Modifier</a>
                    <a href="supprimer_prof.php?id=<?= $prof['id'] ?>" class="delete" onclick="return confirm('Supprimer ce professeur ?')">❌ Supprimer</a>
                    <a href="#">📂 Détails</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
