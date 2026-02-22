<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Admin</title>
    <link rel="stylesheet" href="../assets/css/login_admin.css">
</head>
<body>

    <div class="login-box">
        <h2>Connexion Admin</h2>

        <form method="POST" action="traitement_admin.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
            <a href="admin_oublie.php">Mot de passe oublié ?</a>
            <a href="inscription_admin.php" class="btn">Pas encore inscrit ? Vite inscris toi</a>
        </form>

        <a class="back-link" href="../index.php">← Retour à l'accueil</a>
    </div>

</body>
</html>
