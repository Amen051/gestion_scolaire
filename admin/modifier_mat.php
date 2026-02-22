<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}

$message = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: matiere.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM matieres WHERE id = ?");
$stmt->execute([$id]);
$matiere = $stmt->fetch();

if (!$matiere) {
    $message = "⚠️ Matière introuvable.";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = trim($_POST['nom']);
        $coef = intval($_POST['coefficient']);

        if (!empty($nom) && $coef > 0) {
            $update = $pdo->prepare("UPDATE matieres SET nom_matiere = ?, coefficient = ? WHERE id = ?");
            if ($update->execute([$nom, $coef, $id])) {
                $message = "✅ Matière mise à jour avec succès.";
                header("Location: ajouter_matiere.php");
                exit();
            } else {
                $message = "❌ Échec de la mise à jour.";
            }
        } else {
            $message = "⚠️ Champs invalides.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Matière</title>
    <link rel="stylesheet" href="../assets/css/matiere.css">
</head>
<body>
<div class="container">
    <h2>✏️ Modifier une Matière</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($matiere): ?>
        <form method="POST">
            <label for="nom">Nom de la matière :</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($matiere['nom_matiere']) ?>" required>

           
            <button type="submit">Mettre à jour</button>
            <a href="matiere.php" class="cancel">Annuler</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
