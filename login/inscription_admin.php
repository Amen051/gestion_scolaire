<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Admin</title>
    <link rel="stylesheet" href="../assets/css/inscription_admin.css">
</head>
<body>
    <div class="form-box">
        <h2>Créer un compte admin</h2>
        <form method="POST" action="traitement_inscription_admin.php">
            <input type="text" name="nom" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe : 12 caracteres minimum, min,majus,chiffres,caractères spéciaux" required>
            <button type="submit">Créer le compte</button>
        </form>
         <a class="back-link" href="../index.php">← Retour à l'accueil</a>
    </div>
</body>
</html>
