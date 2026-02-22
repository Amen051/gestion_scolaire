<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
$nom = $_SESSION['admin_nom'];

$nb_eleves = $pdo->query("SELECT COUNT(*) FROM eleves")->fetchColumn();

$nb_profs = $pdo->query("SELECT COUNT(*) FROM professeurs")->fetchColumn();

$nb_matieres = $pdo->query("SELECT COUNT(DISTINCT nom_matiere) FROM matieres")->fetchColumn();

$total_ecolage = $pdo->query("SELECT SUM(ecolage) FROM eleves")->fetchColumn();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Admin</title>
  <link rel="stylesheet" href="../assets/css/dash_admin.css">
</head>
<body>

<header>
    <h1>Bienvenue Admin <?= htmlspecialchars($nom) ?></h1>
</header>

<nav>
    <a href="professeurs.php">➕ Gérer les Professeurs</a>
    <a href="eleves.php">👨‍🎓 Gérer les Élèves</a>
    <a href="bulletins.php">📤 Envoyer Messages</a>
    <a href="matiere.php">📚 Enregistrement des matières</a>
    <a href="actualites.php">Diffusions des actualités</a>
    <a href="messages.php">📤 Messages des internautes</a>
    <a href="logout.php" class="logout">🔓 Déconnexion</a>
</nav>

<div class="container">
    <div class="box">
        <h2>Vue générale</h2>
        <p>Utilisez les liens ci-dessus pour gérer les utilisateurs, bulletins, professeurs et la communication avec les parents.</p>
    </div>

    <div class="box">
        <h3>À faire rapidement :</h3>
        <ul>
            <li>✔️ Ajouter les enseignants nouvellement recrutés</li>
            <li>✔️ Vérifier les notes déposées par les professeurs</li>
            <li>✔️ Informer les parents par mail dès que les bulletins sont prêts</li>
        </ul>
    </div>
    <div class="stats-container">
    <div class="stat-card" style="background:#3498db;">
        <h3>Élèves</h3>
        <p><?= $nb_eleves ?></p>
    </div>

    <div class="stat-card" style="background:#2ecc71;">
        <h3>Professeurs</h3>
        <p><?= $nb_profs ?></p>
    </div>

    <div class="stat-card" style="background:#9b59b6;">
        <h3>Matières</h3>
        <p><?= $nb_matieres ?></p>
    </div>

    <div class="stat-card" style="background:#e67e22;">
        <h3>Écolages (FCFA)</h3>
        <p><?= number_format($total_ecolage, 0, ',', ' ') ?></p>
    </div>
</div>


</div>

</body>
</html>
