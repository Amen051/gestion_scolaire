<?php
require_once '../includes/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare("UPDATE admins SET is_active = 1, validation_token = NULL WHERE validation_token = ?");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        echo "<div style='text-align:center; margin-top:50px;'>
                <h2 style='color:green;'>Accès Administrateur Activé !</h2>
                <p><a href='login_admin.php'>Accéder au panneau de contrôle</a></p>
              </div>";
    } else {
        echo "<p style='color:red; text-align:center;'>Lien invalide ou expiré.</p>";
    }
}