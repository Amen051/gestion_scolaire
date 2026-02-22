<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['prof_id'])) {
    header("Location: ../login/login_prof.php");
    exit();
}

$prof_id = $_SESSION['prof_id'];
$trimestre = $_POST['trimestre'] ?? '';
$classe = $_POST['classe'] ?? '';

$stmt = $pdo->prepare("SELECT DISTINCT classe FROM enseignement WHERE prof_id = ?");
$stmt->execute([$prof_id]);
$classes = $stmt->fetchAll();

$eleves = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $classe && $trimestre) {
    $stmt = $pdo->prepare("SELECT id, nom, prenom FROM eleves WHERE classe = ?");
    $stmt->execute([$classe]);
    $eleves = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir Bulletins</title>
    <link rel="stylesheet" href="../assets/css/liste_bul.css">
</head>
<body>
<a href="dashboard.php" class="btn">⬅️ Retour</a>
<h2>📘 Bulletins des Élèves</h2>

<form method="POST">
    <label>Classe :
        <select name="classe" required>
            <option value="">-- Choisir une classe --</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= htmlspecialchars($c['classe']) ?>" <?= ($classe == $c['classe']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['classe']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Trimestre :
        <select name="trimestre" required>
            <option value="">-- Choisir un trimestre --</option>
            <option value="Trimestre 1" <?= ($trimestre == 'Trimestre 1') ? 'selected' : '' ?>>Trimestre 1</option>
            <option value="Trimestre 2" <?= ($trimestre == 'Trimestre 2') ? 'selected' : '' ?>>Trimestre 2</option>
            <option value="Trimestre 3" <?= ($trimestre == 'Trimestre 3') ? 'selected' : '' ?>>Trimestre 3</option>
        </select>
    </label>

    <input type="submit" value="Afficher les élèves">
</form>

<?php if (!empty($eleves)): ?>
    <h3>🎓 Liste des élèves (<?= htmlspecialchars($classe) ?> - <?= htmlspecialchars($trimestre) ?>)</h3>
    <table border="1" cellpadding="8">
        <tr><th>Nom</th><th>Prénom</th><th>Bulletin</th></tr>
        <?php foreach ($eleves as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><a href="bul.php?eleve_id=<?= $e['id'] ?>&trimestre=<?= urlencode($trimestre) ?>" target="_blank">📄 Voir bulletin</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
