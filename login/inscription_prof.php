<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Professeur</title>
    <link rel="stylesheet" href="../assets/css/inscription_prof.css">
</head>
<body>
    <div class="form-box">
        <h2>Créer un compte Professeur</h2>
        <form method="POST" action="traitement_inscription_prof.php">
            <input type="text" name="nom" placeholder="Nom complet et prénoms" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe:12 caractères minimum,maj,min,chiffre" required>
            <input type="text" name="matiere" placeholder="Numéro de telephone" required>
            <button type="submit">Créer le compte</button>
        </form>
    </div>
</body>
</html>
