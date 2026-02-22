<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}


$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);

    if (!empty($nom) > 0) {
        $stmt = $pdo->prepare("INSERT INTO matieres (nom_matiere) VALUES (?)");
        if ($stmt->execute([$nom])) {
            $message = "✅ Matière enregistrée avec succès.";
        } else {
            $message = "❌ Une erreur est survenue.";
        }
    } else {
        $message = "⚠️ Veuillez remplir correctement les champs.";
    }
}
$stmt = $pdo->query("SELECT * FROM matieres ORDER BY nom_matiere ASC");
$matieres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Matière</title>
    <link rel="stylesheet" href="../assets/css/matiere.css">
</head>
<body>
<div class="container">
    
    <h2>Ajouter une Matière</h2>
<a href="dashboard.php">⬅️ Retour à l'espace admin</a>
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="nom">Nom de la matière :</label>
        <input type="text" name="nom" id="nom" required>

        <button type="submit">Enregistrer</button>
    </form>

    <hr style="margin: 30px 0;">

    <h3>📚 Liste des matières enregistrées</h3>

    <?php if (count($matieres) > 0): ?>
        <table class="table-matieres">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Date d'ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matieres as $index => $matiere): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($matiere['nom_matiere']) ?></td>
                        <td><?= date('d/m/Y', strtotime($matiere['created_at'])) ?></td>
                        <td class="actions">
                    <a class="edit" href="modifier_mat.php?id=<?= $matiere['id'] ?>">✏️ Modifier</a>
                    <a class="delete" href="supprimer_mat.php?id=<?= $matiere['id'] ?>" onclick="return confirm('Supprimer cet enseignement ?')">❌ Supprimer</a>
                </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune matière enregistrée pour l’instant.</p>
    <?php endif; ?>
</div>
</body>
</html>