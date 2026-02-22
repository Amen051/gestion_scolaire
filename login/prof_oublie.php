<?php
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php';

$step = 1; 
$msg = "";
$email_temp = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<p style='color:red;'>Erreur : Format d'email incorrect.</p>");
    }

    if (isset($_POST['action']) && $_POST['action'] == 'send_token') {
        $check = $pdo->prepare("SELECT id FROM professeurs WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->rowCount() > 0) {
            $token = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6)); 
            $pdo->prepare("UPDATE professeurs SET reset_token = ? WHERE email = ?")->execute([$token, $email]);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'votre-mail@gmail.com';
                $mail->Password = 'mot_de_passe';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('no-reply@', 'CSBJS Support');
                $mail->addAddress($email);
                $mail->Subject = 'Code de réinitialisation';
                $mail->Body = "Votre code de sécurité est : $token";

                $mail->send();
                $step = 2; 
                $email_temp = $email;
                $msg = "<p style='color:green;'>Un code a été envoyé à votre adresse email.</p>";
            } catch (Exception $e) {
                $msg = "<p style='color:red;'>Erreur d'envoi : {$mail->ErrorInfo}</p>";
            }
        } else {
            $msg = "<p style='color:red;'>Utilisateur inconnu.</p>";
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'reset_password') {
        $token_saisi = trim($_POST['token']);
        $new_pass = $_POST['password'];
        $email_temp = $email;

        $regexPass = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';
        if (!preg_match($regexPass, $new_pass)) {
            $msg = "<p style='color:red;'>Le mot de passe ne respecte pas les critères (12 car, Maj, Chiffre, Spécial).</p>";
            $step = 2;
        } else {
            
            $stmt = $pdo->prepare("SELECT id FROM professeurs WHERE email = ? AND reset_token = ?");
            $stmt->execute([$email, $token_saisi]);

            if ($stmt->rowCount() > 0) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE professeurs SET mot_de_passe = ?, reset_token = NULL WHERE email = ?")->execute([$hashed, $email]);
                $msg = "<p style='color:green;'>Mot de passe modifié avec succès ! <a href='login_prof.php'>Se connecter</a></p>";
                $step = 3; 
            } else {
                $msg = "<p style='color:red;'>Code de sécurité incorrect.</p>";
                $step = 2;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe</title>
    <link rel="stylesheet" href="../assets/css/login_prof.css">
</head>
<body>
    <div class="login-box">
        <h2>Réinitialisation</h2>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email_temp) ?>" required <?= ($step >= 2) ? 'readonly' : '' ?>>

            <?php if ($step == 1): ?>
                <input type="hidden" name="action" value="send_token">
                <input type="submit" value="Envoyer le code">
            <?php endif; ?>

            <?php if ($step == 2): ?>
                <input type="text" name="token" placeholder="Entrez le code reçu par mail" required>
                <input type="password" name="password" placeholder="Nouveau mot de passe (12 car. min)" required>
                <input type="hidden" name="action" value="reset_password">
                <input type="submit" value="Mettre à jour">
            <?php endif; ?>
        </form>

        <?= $msg ?>
        <br>
        <a class="back-link" href="login_prof.php">← Retour à la connexion</a>
    </div>
</body>
</html>