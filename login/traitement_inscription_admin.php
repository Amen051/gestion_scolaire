<?php
require_once '../includes/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $regexPass = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';
    if (!preg_match($regexPass, $password)) {
        die("<p style='color:red; text-align:center;'>Sécurité Admin : 12 caractères minimum;  min, Majuscule, Chiffre et Caractère spécial requis.</p>");
    }
	$regexNom = "/^[a-zA-ZÀ-ÿ\s\-\']+$/u";

	if (!is_string($nom) || !preg_match($regexNom, $nom)) {
    	die("<p style='color:red;'>Erreur : Le nom contient des caractères non autorisés ou est invalide.</p>");
	}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<p style='color:red; text-align:center;'>Format d'email invalide.</p>");
    }

    $check = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        die("<p style='color:red; text-align:center;'>Cet email est déjà utilisé par un administrateur.</p>");
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare("INSERT INTO admins (nom, email, mot_de_passe, validation_token, is_active) VALUES (?, ?, ?, ?, 0)");
    
    if ($stmt->execute([$nom, $email, $hashed, $token])) {
        
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vous@gmail.com'; 
            $mail->Password   = 'mot_de_passe'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('no-reply@s', 'Direction CSBJS');
            $mail->addAddress($email, $nom);

            $url_validation = "login/valider_admin.php?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Activation de votre accès Administrateur';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px;'>
                    <h2 style='color: #d32f2f;'>Alerte Sécurité : Nouvel Administrateur</h2>
                    <p>Bonjour <strong>$nom</strong>,</p>
                    <p>Un compte administrateur a été créé avec cet email. Pour l'activer, cliquez sur le bouton ci-dessous :</p>
                    <p style='text-align: center;'>
                        <a href='$url_validation' style='padding:12px 25px; background:#d32f2f; color:white; text-decoration:none; border-radius:5px; font-weight:bold;'>ACTIVER MON COMPTE ADMIN</a>
                    </p>
                    <p style='font-size: 12px; color: #777;'>Si vous n'êtes pas à l'origine de cette demande, contactez immédiatement le service informatique.</p>
                </div>";

            $mail->send();
            echo "<p style='color:green;text-align:center;'>Compte Admin créé ! Un mail de validation prioritaire a été envoyé.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>Erreur d'envoi du mail : {$mail->ErrorInfo}</p>";
        }
    }
}
?>