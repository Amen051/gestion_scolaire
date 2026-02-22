<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Scolaire - Accueil</title>
    <link rel="stylesheet" href="assets/css/connect.css">
</head>
<body>
    <div class="container">
        <img src="assets/img/logo.png" alt="Logo École" class="logo">
        <h1>Bienvenue sur le système de gestion scolaire</h1>
        <p>Choisissez votre espace :</p>
        <a href="login/login_admin.php" class="btn">Espace Admin</a>
        <a href="login/login_prof.php" class="btn">Espace Professeur</a>
    </div>
</body>
</html>
