<?php
session_start();
if (!isset($_SESSION['prof_id'])) {
    header("Location: ../login/login_prof.php");
    exit();
}

require_once '../includes/db.php';

$prof_id = $_SESSION['prof_id'];

$stmt = $pdo->prepare("SELECT DISTINCT classe, matiere FROM enseignement WHERE prof_id = ?");
$stmt->execute([$prof_id]);
$cours = $stmt->fetchAll();

$erreur = '';
$success = '';

$notes_existantes = [];
if (isset($_POST['classe'], $_POST['matiere'], $_POST['trimestre'])) {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE professeur_id = ? AND classe = ? AND matiere = ? AND trimestre = ?");
    $stmt->execute([$prof_id, $_POST['classe'], $_POST['matiere'], $_POST['trimestre']]);
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        $notes_existantes[$row['eleve_id']] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_notes'])) {
    $classe = $_POST['classe'];
    $matiere = $_POST['matiere'];
    $coefficient = $_POST['coefficient'];
    $trimestre = $_POST['trimestre'];

    if (!empty($_POST['note_devoir']) && !empty($_POST['note_composition'])) {
        foreach ($_POST['note_devoir'] as $eleve_id => $note_dev) {
            $note_comp = $_POST['note_composition'][$eleve_id];

            $stmt = $pdo->prepare("SELECT id FROM notes WHERE eleve_id=? AND professeur_id=? AND classe=? AND matiere=? AND trimestre=?");
            $stmt->execute([$eleve_id, $prof_id, $classe, $matiere, $trimestre]);
            $note_exist = $stmt->fetch();

            if ($note_exist) {
                
                $stmt = $pdo->prepare("UPDATE notes SET note_devoir = ?, note_composition = ?, coefficient = ? WHERE id = ?");
                $stmt->execute([$note_dev, $note_comp, $coefficient, $note_exist['id']]);
            } else {
                
                $stmt = $pdo->prepare("INSERT INTO notes (eleve_id, professeur_id, matiere, classe, coefficient, note_devoir, note_composition, trimestre)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$eleve_id, $prof_id, $matiere, $classe, $coefficient, $note_dev, $note_comp, $trimestre]);
            }
        }
        $success = "✅ Notes enregistrées ou mises à jour avec succès !";
    } else {
        $erreur = "⚠️ Veuillez remplir toutes les notes.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes des Élèves</title>
    <link rel="stylesheet" href="../assets/css/notes.css">
</head>
<body>
<a href="dashboard.php" class="btn">⬅️ Retour à l'espace professeur</a>
<h2>📝 Enregistrement des notes</h2>

<?php if (!empty($success)): ?>
    <div class="msg success"><?= $success ?></div>
<?php elseif (!empty($erreur)): ?>
    <div class="msg error"><?= $erreur ?></div>
<?php endif; ?>

<form method="POST">
    <label>Classe :
        <select name="classe" required onchange="this.form.submit()">
            <option value="">-- Choisir une classe --</option>
            <?php foreach ($cours as $c): ?>
                <option value="<?= $c['classe'] ?>" <?= (isset($_POST['classe']) && $_POST['classe'] == $c['classe']) ? 'selected' : '' ?>>
                    <?= $c['classe'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Matière :
        <select name="matiere" required onchange="this.form.submit()">
            <option value="">-- Choisir matière --</option>
            <?php foreach ($cours as $c): ?>
                <option value="<?= $c['matiere'] ?>" <?= (isset($_POST['matiere']) && $_POST['matiere'] == $c['matiere']) ? 'selected' : '' ?>>
                    <?= $c['matiere'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Coefficient :
        <input type="number" name="coefficient" required min="1" value="<?= $_POST['coefficient'] ?? 1 ?>">
    </label>

    <label>Trimestre :
        <select name="trimestre" required onchange="this.form.submit()">
            <option value="">-- Choisir trimestre --</option>
            <option value="Trimestre 1" <?= (isset($_POST['trimestre']) && $_POST['trimestre'] == 'Trimestre 1') ? 'selected' : '' ?>>Trimestre 1</option>
            <option value="Trimestre 2" <?= (isset($_POST['trimestre']) && $_POST['trimestre'] == 'Trimestre 2') ? 'selected' : '' ?>>Trimestre 2</option>
            <option value="Trimestre 3" <?= (isset($_POST['trimestre']) && $_POST['trimestre'] == 'Trimestre 3') ? 'selected' : '' ?>>Trimestre 3</option>
        </select>
    </label>

    <?php
    if (isset($_POST['classe'])) {
        $classe = $_POST['classe'];
        $stmt = $pdo->prepare("SELECT * FROM eleves WHERE classe = ?");
        $stmt->execute([$classe]);
        $eleves = $stmt->fetchAll();

        if ($eleves): ?>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Note Devoir /20</th>
                    <th>Note Composition /20</th>
                </tr>
                <?php foreach ($eleves as $e): 
                    $note = $notes_existantes[$e['id']] ?? null;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($e['nom']) ?></td>
                        <td><?= htmlspecialchars($e['prenom']) ?></td>
                        <td>
                            <input type="number" name="note_devoir[<?= $e['id'] ?>]" step="0.01" min="0" max="20" required
                                   value="<?= $note['note_devoir'] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" name="note_composition[<?= $e['id'] ?>]" step="0.01" min="0" max="20" required
                                   value="<?= $note['note_composition'] ?? '' ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <input type="submit" name="submit_notes" value="💾 Enregistrer les notes">
        <?php else: ?>
            <p style="text-align: center; margin-top: 20px;">Aucun élève trouvé dans cette classe.</p>
        <?php endif;
    }
    ?>
</form>

</body>
</html>
