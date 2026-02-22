<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: professeurs.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM professeurs WHERE id = ?");
$stmt->execute([$id]);
$prof = $stmt->fetch();

if (!$prof) {
    die("Professeur introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $matiere = trim($_POST['telephone']);

    if ($nom || $email || $matiere) {
        $update = $pdo->prepare("UPDATE professeurs SET nom = ?, email = ?, telephone = ? WHERE id = ?");
        $update->execute([$nom, $email, $matiere, $id]);
        header("Location: professeurs.php");
        exit();
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Professeur</title>
    <link rel="stylesheet" href="../assets/css/modif_prof.css">
</head>
<body>
    <div class="container">
        <h2>Modifier les informations du professeur</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($prof['nom']) ?>" required>

            <label>Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($prof['email']) ?>" required>

            <label>Numéro de téléphone :</label>
            <input type="text" name="telephone" value="<?= htmlspecialchars($prof['telephone']) ?>" required>

            <button type="submit">✅ Modifier</button>
        </form>
        <a href="professeurs.php">⬅️ Retour</a>
    </div>
</body>

</html>
