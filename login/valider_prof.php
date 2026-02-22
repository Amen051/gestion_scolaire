<?php
require_once '../includes/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("UPDATE professeurs SET est_valide = 1, token = NULL WHERE token = ?");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        echo "Votre compte est activé ! <a href='login_prof.php'>Connectez-vous ici</a>";
    } else {
        echo "Lien invalide ou compte déjà activé.";
    }
}