<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Professeur</title>
<link rel="stylesheet" href="../assets/css/login_prof.css">
</head>
<body>

    <div class="login-box">
        <h2>Connexion Professeur</h2>

        <form method="POST" action="traitement_prof.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
            <a href="prof_oublie.php">Mot de passe oublié ?</a>
        </form>
         <a href="inscription_prof.php" class="btn">Pas encore inscrit ? Vite inscris toi</a>
        <a class="back-link" href="../index.php">← Retour à l'accueil</a>
    </div>

</body>
</html>
