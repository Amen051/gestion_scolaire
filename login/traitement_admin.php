<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
        
        if ($admin['is_active'] == 0) {
            echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>";
            echo "<p style='color:orange; font-weight:bold;'>⚠️ Accès restreint : Votre compte n'est pas encore activé.</p>";
            echo "<p>Veuillez cliquer sur le lien de validation envoyé à <strong>" . htmlspecialchars($email) . "</strong>.</p>";
            echo "<p><a href='login_admin.php'>⟵ Retour</a></p>";
            echo "</div>";
            exit();
        }

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
       

        header('Location: ../admin/dashboard.php');
        exit();
        
    } else {
        echo "<p style='color:red;text-align:center;'>Email ou mot de passe incorrect</p>";
        echo "<p style='text-align:center;'><a href='login_admin.php'>⟵ Retour</a></p>";
    }
} else {
    header('Location: login_admin.php');
    exit();
}