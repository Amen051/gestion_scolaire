<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../login/login_admin.php'); exit(); }
require_once '../includes/db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM actualites WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: gestion_actus.php');
}

$actus = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les actualités</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Liste des Actualités</h1>
        <a href="actualites.php" class="btn-add"> + Ajouter une actu</a>
        
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actus as $a): ?>
                <tr>
                    <td><img src="../assets/img/actus/<?= $a['image_url'] ?: 'default.png' ?>" width="50"></td>
                    <td><?= htmlspecialchars($a['titre']) ?></td>
                    <td><?= date('d/m/Y', strtotime($a['date_publication'])) ?></td>
                    <td><?= $a['statut'] ?></td>
                    <td>
                        <a href="?delete=<?= $a['id'] ?>" onclick="return confirm('Supprimer ?')" style="color:red;">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php">← Retour</a>
    </div>
</body>
</html>