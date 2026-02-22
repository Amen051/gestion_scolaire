<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}

require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: enseignement.php');
    exit();
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM enseignement WHERE id = ?");
$stmt->execute([$id]);
$enseignement = $stmt->fetch();

if (!$enseignement) {
    header('Location: enseignement.php');
    exit();
}

$profs = $pdo->query("SELECT id, nom FROM professeurs")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prof_id = intval($_POST['prof_id']);
    $classe = trim($_POST['classe']);
    $matiere = trim($_POST['matiere']);
    $jour = trim($_POST['jour']);
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    if ($prof_id && $classe && $matiere && $jour && $heure_debut && $heure_fin) {
        $stmt = $pdo->prepare("UPDATE enseignement SET prof_id = ?, classe = ?, matiere = ?, jour = ?, heure_debut = ?, heure_fin = ? WHERE id = ?");
        $stmt->execute([$prof_id, $classe, $matiere, $jour, $heure_debut, $heure_fin, $id]);
        header("Location: enseignement.php");
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
    <title>Modifier Enseignement</title>
   <link rel="stylesheet" href="../assets/css/modif_enseign.css">
</head>
<body>

    <div class="container">
        <h2>✏️ Modifier un Enseignement</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Professeur :</label>
            <select name="prof_id" required>
                <?php foreach ($profs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($p['id'] == $enseignement['prof_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Classe :</label>
            <input type="text" name="classe" value="<?= htmlspecialchars($enseignement['classe']) ?>" required>

            <label>Matière :</label>
            <input type="text" name="matiere" value="<?= htmlspecialchars($enseignement['matiere']) ?>" required>

            <label>Jour :</label>
            <select name="jour" required>
                <?php
                $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
                foreach ($jours as $jour):
                ?>
                    <option value="<?= $jour ?>" <?= ($jour == $enseignement['jour']) ? 'selected' : '' ?>>
                        <?= $jour ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Heure de début :</label>
            <input type="time" name="heure_debut" value="<?= $enseignement['heure_debut'] ?>" required>

            <label>Heure de fin :</label>
            <input type="time" name="heure_fin" value="<?= $enseignement['heure_fin'] ?>" required>

            <button type="submit">✅ Enregistrer les modifications</button>
        </form>

        <a href="enseignement.php">⬅️ Retour à la liste</a>
    </div>

</body>
</html>
