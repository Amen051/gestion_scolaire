<?php
session_start();
if (!isset($_SESSION['prof_id'])) {
    header("Location: ../login/login_prof.php");
    exit();
}

require_once '../includes/db.php';

$prof_id = $_SESSION['prof_id'];
$stmt = $pdo->prepare("SELECT nom FROM professeurs WHERE id = ?");
$stmt->execute([$prof_id]);
$prof = $stmt->fetch();

$stmt1 = $pdo->prepare("SELECT COUNT(DISTINCT matiere) as nb_matieres FROM enseignement WHERE prof_id = ?");
$stmt1->execute([$prof_id]);
$nb_matieres = $stmt1->fetchColumn();

$stmt2 = $pdo->prepare("SELECT COUNT(DISTINCT classe) as nb_classes FROM enseignement WHERE prof_id = ?");
$stmt2->execute([$prof_id]);
$nb_classes = $stmt2->fetchColumn();

$stmt3 = $pdo->prepare("SELECT SUM(TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin)) as total_minutes FROM enseignement WHERE prof_id = ?");
$stmt3->execute([$prof_id]);
$total_minutes = $stmt3->fetchColumn();
$total_heures = round($total_minutes / 60, 2);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Professeur</title>
     <link rel="stylesheet" href="../assets/css/dash_prof.css">
</head>
<body>

    <h1>Bienvenue <?= htmlspecialchars( $prof['nom']) ?> 👋🏾</h1>

    <div class="stats">
        <div class="card">
            <h2><?= $nb_matieres ?></h2>
            <p>Matières enseignées</p>
        </div>
        <div class="card">
            <h2><?= $nb_classes ?></h2>
            <p>Classes concernées</p>
        </div>
        <div class="card">
            <h2><?= $total_heures ?> h</h2>
            <p>Total heures de cours</p>
        </div>
    </div>

    <div class="actions">
        <a href="suivi_personnel.php">📨 Suivi des élèves</a>
        <a href="notes.php">📝 Gérer les notes</a>
        <a href="emploi.php">📅 Mon emploi du temps</a>
        <a href="liste_bulletin.php">📜 Voir les bulletins</a>
        <a href="logout.php">🚪 Déconnexion</a>
    </div>

</body>
</html>
