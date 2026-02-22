<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}

require_once '../includes/db.php';

$classeSelectionnee = null;
$eleves = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classe'])) {
    $classeSelectionnee = $_POST['classe'];

    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE classe = ?");
    $stmt->execute([$classeSelectionnee]);
    $eleves = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Élèves</title>
    <link rel="stylesheet" href="../assets/css/liste_eleves.css">
</head>
<body>
<a href="dashboard.php" class="btn">⬅️ Retour à l'espace admin</a>
    <h1>📋 Liste des Élèves par Classe</h1>

    <div class="class-buttons">
        <form method="post"><input type="hidden" name="classe" value="6ème"><button>6ème</button></form>
        <form method="post"><input type="hidden" name="classe" value="5ème"><button>5ème</button></form>
        <form method="post"><input type="hidden" name="classe" value="4ème"><button>4ème</button></form>
        <form method="post"><input type="hidden" name="classe" value="3ème"><button>3ème</button></form>
    </div>

    <?php if ($classeSelectionnee): ?>
        <h2 style="text-align:center;">Classe sélectionnée : <strong><?= htmlspecialchars($classeSelectionnee) ?></strong></h2>

        <?php if (count($eleves) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Sexe</th>
                        <th>Contact Parent</th>
                        <th>Email Parent</th>
                        <th>Écolage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves as $eleve): ?>
                        <tr>
                            <td><?= htmlspecialchars($eleve['nom']) ?></td>
                            <td><?= htmlspecialchars($eleve['prenom']) ?></td>
                            <td><?= htmlspecialchars($eleve['date_naissance']) ?></td>
                            <td><?= htmlspecialchars($eleve['sexe']) ?></td>
                            <td><?= htmlspecialchars($eleve['contact_parent']) ?></td>
                            <td><?= htmlspecialchars($eleve['email_parent']) ?></td>
                            <td><?= number_format($eleve['ecolage'], 0, ',', ' ') ?> FCFA</td>
                            <td class="action-buttons">
                                <a href="modifier_eleves.php?id=<?= $eleve['id'] ?>" class="modifier">Modifier</a>
                                <a href="supprimer_eleves.php?id=<?= $eleve['id'] ?>" class="supprimer" onclick="return confirm('Supprimer cet élève ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun élève inscrit dans cette classe.</p>
        <?php endif ?>
    <?php endif ?>

</body>
</html>