<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prof_id = intval($_POST['prof_id']);
    $classe = trim($_POST['classe']);
    $matiere = trim($_POST['matiere']);
    $jour = trim($_POST['jour']);
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    if ($prof_id && $classe && $matiere && $jour && $heure_debut && $heure_fin) {
        $stmt = $pdo->prepare("INSERT INTO enseignement (prof_id, classe, matiere, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prof_id, $classe, $matiere, $jour, $heure_debut, $heure_fin]);
        header("Location: enseignement.php");
        exit();
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

$profs = $pdo->query("SELECT id, nom FROM professeurs")->fetchAll();
$mats = $pdo->query("SELECT nom_matiere FROM matieres")->fetchAll();

$sql = "SELECT e.*, p.nom as nom_prof
        FROM enseignement e
        JOIN professeurs p ON p.id = e.prof_id
        ORDER BY p.nom, e.jour, e.heure_debut";
$enseignements = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Enseignements</title>
     <link rel="stylesheet" href="../assets/css/enseignement.css">
</head>
<body>

    <h1>📚 Gestion des Enseignements</h1>

    <div class="form-box">
        <h3>➕ Ajouter un enseignement</h3>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label for="prof_id">Professeur :</label>
            <select name="prof_id" required>
                <option value="">-- Choisir un professeur --</option>
                <?php foreach ($profs as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Classe :</label>
            <select name="classe" required>
                <option value="">-- Choisir une classe --</option>
                <option>6eme</option>
                <option>5eme</option>
                <option>4eme</option>
                <option>3eme</option>
            </select>

            <label>Matière :</label>
            <select name="matiere" required>
                <option value="">-- Choisir une matiere --</option>
                <?php foreach ($mats as $m): ?>
                    <option value="<?= $m['nom_matiere'] ?>"><?= htmlspecialchars($m['nom_matiere']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Jour :</label>
            <select name="jour" required>
                <option value="">-- Choisir un jour --</option>
                <option>Lundi</option>
                <option>Mardi</option>
                <option>Mercredi</option>
                <option>Jeudi</option>
                <option>Vendredi</option>
                <option>Samedi</option>
            </select>

            <label>Heure de début :</label>
            <input type="time" name="heure_debut" required>

            <label>Heure de fin :</label>
            <input type="time" name="heure_fin" required>

            <button type="submit">✅ Ajouter</button>
        </form>
        <a href="professeurs.php">⬅️ Retour à la liste</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Professeur</th>
                <th>Matière</th>
                <th>Classe</th>
                <th>Jour</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($enseignements as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nom_prof']) ?></td>
                <td><?= htmlspecialchars($e['matiere']) ?></td>
                <td><?= htmlspecialchars($e['classe']) ?></td>
                <td><?= htmlspecialchars($e['jour']) ?></td>
                <td><?= substr($e['heure_debut'], 0, 5) ?></td>
                <td><?= substr($e['heure_fin'], 0, 5) ?></td>
                <td class="actions">
                    <a class="edit" href="modifier_enseignement.php?id=<?= $e['id'] ?>">✏️ Modifier</a>
                    <a class="delete" href="supprimer_enseignement.php?id=<?= $e['id'] ?>" onclick="return confirm('Supprimer cet enseignement ?')">❌ Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
