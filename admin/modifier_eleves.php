<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login_admin.php");
    exit();
}

require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: liste_eleves.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM eleves WHERE id = ?");
$stmt->execute([$id]);
$eleve = $stmt->fetch();

if (!$eleve) {
    echo "Élève introuvable.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $classe = $_POST['classe'];
    $contact_parent = $_POST['contact_parent'];
    $email_parent = $_POST['email_parent'];
    $ecolage = floatval($_POST['ecolage']);

    $stmt = $pdo->prepare("UPDATE eleves SET nom=?, prenom=?, date_naissance=?, sexe=?, classe=?, contact_parent=?, email_parent=?, ecolage=? WHERE id=?");
    $stmt->execute([$nom, $prenom, $date_naissance, $sexe, $classe, $contact_parent, $email_parent, $ecolage, $id]);

    header("Location: liste_eleves.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Élève</title>
     <link rel="stylesheet" href="../assets/css/modifier_eleves.css">
</head>
<body>
    <div class="container">
        <h2>✏️ Modifier l'Élève</h2>
        <form method="post">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($eleve['nom']) ?>" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($eleve['prenom']) ?>" required>

            <label>Date de naissance :</label>
            <input type="date" name="date_naissance" value="<?= $eleve['date_naissance'] ?>" required>

            <label>Sexe :</label>
            <select name="sexe" required>
                <option value="M" <?= $eleve['sexe'] === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                <option value="F" <?= $eleve['sexe'] === 'Féminin' ? 'selected' : '' ?>>Féminin</option>
            </select>

            <label>Classe :</label>
            <select name="classe" required>
                <option value="6ème" <?= $eleve['classe'] === '6ème' ? 'selected' : '' ?>>6ème</option>
                <option value="5ème" <?= $eleve['classe'] === '5ème' ? 'selected' : '' ?>>5ème</option>
                <option value="4ème" <?= $eleve['classe'] === '4ème' ? 'selected' : '' ?>>4ème</option>
                <option value="3ème" <?= $eleve['classe'] === '3ème' ? 'selected' : '' ?>>3ème</option>
            </select>

            <label>Contact du parent :</label>
            <input type="text" name="contact_parent" value="<?= htmlspecialchars($eleve['contact_parent']) ?>" required>

            <label>Email du parent :</label>
            <input type="email" name="email_parent" value="<?= htmlspecialchars($eleve['email_parent']) ?>" required>

            <label>Écolage :</label>
            <input type="number" step="0.01" name="ecolage" value="<?= htmlspecialchars($eleve['ecolage']) ?>" required>

            <button type="submit">✅ Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>