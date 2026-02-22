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
    $contact = trim($_POST['matiere']);

   

    // Mot de passe : 12 car., Maj, Min, Chiffre, Spécial
    $regexPass = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/';
    if (!preg_match($regexPass, $password)) {
        die("<p style='color:red;'>Sécurité du mot de passe: 12 caractères minimum,Minuscule, Majuscule, Chiffre et Caractère spécial requis.</p>");
    }
    $regexNom = "/^[a-zA-ZÀ-ÿ\s\-\']+$/u";

	if (!is_string($nom) || !preg_match($regexNom, $nom)) {
    	die("<p style='color:red;'>Erreur : Le nom contient des caractères non autorisés ou est invalide.</p>");
	}

    if (!preg_match('/^[0-9]{8}$/', $contact)) {
        die("<p style='color:red;'>Erreur : Le numéro doit comporter exactement 8 chiffres.</p>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<p style='color:red;'>Erreur : Format d'email incorrect.</p>");
    }

    $check = $pdo->prepare("SELECT id FROM professeurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        die("<p style='color:red;'>Erreur : Cet email est déjà enregistré.</p>");
    }


    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32)); 

    $stmt = $pdo->prepare("INSERT INTO professeurs (nom, email, mot_de_passe, telephone, token, est_valide) VALUES (?, ?, ?, ?, ?, 0)");
    
    if ($stmt->execute([$nom, $email, $hashed, $contact, $token])) {
        
       

        $mail = new PHPMailer(true);

        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'toi@gmail.com'; 
            $mail->Password   = 'mot_de_passe'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('no-reply@s', 'CSBJS - Direction');
            $mail->addAddress($email, $nom);

            $url_validation = "login/valider_prof.php?token=" . $token;
            $mail->isHTML(true);
            $mail->Subject = 'Activation de votre compte Professeur';
            $mail->Body    = "
                <h3>Bienvenue au CSBJS, $nom</h3>
                <p>Pour finaliser votre inscription, veuillez cliquer sur le bouton ci-dessous :</p>
                <p><a href='$url_validation' style='padding:10px 20px; background:#2980b9; color:white; text-decoration:none; border-radius:5px;'>Activer mon compte</a></p>
                <p>Si le bouton ne fonctionne pas, copiez ce lien : $url_validation</p>
            ";

            $mail->send();
            echo "<p style='color:green;text-align:center;'>Inscription réussie ! Un email de validation a été envoyé.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}</p>";
        }
    }
}
?>