<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM professeurs WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $prof = $stmt->fetch();

    if ($prof && password_verify($password, $prof['mot_de_passe'])) {
        
        if ($prof['est_valide'] == 0) {
            echo "<p style='color:orange;text-align:center;'>Votre compte n'est pas encore activé. Veuillez vérifier vos emails.</p>";
            echo "<p style='text-align:center;'><a href='login_prof.php'>⟵ Retour</a></p>";
            exit();
        }

        $_SESSION['prof_id'] = $prof['id'];
        $_SESSION['prof_nom'] = $prof['nom'];
        $_SESSION['telephone'] = $prof['telephone'];

        header('Location: ../prof/dashboard.php');
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>Email ou mot de passe incorrect</p>";
        echo "<p style='text-align:center;'><a href='login_prof.php'>⟵ Retour</a></p>";
    }
} else {
    header('Location: login_prof.php');
    exit();
}