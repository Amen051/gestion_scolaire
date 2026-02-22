<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $classe = trim($_POST['classe']);
    $contact_parent = trim($_POST['contact_parent']);
    $email_parent = trim($_POST['email_parent']);
    $ecolage = floatval($_POST['ecolage']);

    if ($nom && $prenom && $date_naissance && $sexe && $classe && $contact_parent && $email_parent) {
        $stmt = $pdo->prepare("INSERT INTO eleves (nom, prenom, date_naissance, sexe, classe, contact_parent, email_parent, ecolage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $date_naissance, $sexe, $classe, $contact_parent, $email_parent, $ecolage]);
        $msg = "Incription de l'élève réussi";
        header("Location: eleves.php");
        exit();
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

$eleves = $pdo->query("SELECT * FROM eleves ORDER BY nom, prenom")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Élèves</title>
    <link rel="stylesheet" href="../assets/css/eleves.css">
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn">⬅️ Retour à l'espace admin</a>
        <h1>🎓 Gestion des Élèves</h1>

        <div class="tabs">
            <a href="eleves.php" class="active">➕ Inscrire un Élève</a>
            <a href="liste_eleves.php">📋 Liste des Élèves</a>
        </div>

        <div id="inscription" class="form-box">
            <?php echo $msg; ?>
            <h3>➕ Formulaire d'inscription</h3>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="post">
                <label>Nom :</label>
                <input type="text" name="nom" required>

                <label>Prénom :</label>
                <input type="text" name="prenom" required>

                <label>Date de naissance :</label>
                <input type="date" name="date_naissance" required>

                <label>Sexe :</label>
                <select name="sexe" required>
                    <option value="">-- Choisir --</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>

                <label>Classe :</label>
                <select name="classe" required>
                <option value="">-- Choisir une classe --</option>
                <option>6eme</option>
                <option>5eme</option>
                <option>4eme</option>
                <option>3eme</option>
            </select>

                <label>Contact du parent :</label>
                <input type="text" name="contact_parent" required>

                <label>Email du parent :</label>
                <input type="email" name="email_parent" required>

                <label>Écolage :</label>
                <input type="number" step="0.01" name="ecolage" required>

                <button type="submit">✅ Enregistrer l'élève</button>
            </form>
        </div>

    
    </div>
</body>
</html>