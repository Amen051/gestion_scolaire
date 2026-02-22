<?php
session_start();
if (!isset($_SESSION['prof_id'])) {
    header("Location: ../login/login_prof.php");
    exit();
}

require '../vendor/autoload.php'; 
require_once '../includes/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$prof_id = $_SESSION['prof_id'];
$success = '';
$error = '';

$stmt = $pdo->prepare("SELECT DISTINCT classe FROM enseignement WHERE prof_id = ?");
$stmt->execute([$prof_id]);
$classes = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $classe = $_POST['classe'];
    $objet = trim($_POST['objet']);
    $message = trim($_POST['message']);

    if (!empty($classe) && !empty($objet) && !empty($message)) {
        $stmt = $pdo->prepare("SELECT nom, prenom, email_parent FROM eleves WHERE classe = ?");
        $stmt->execute([$classe]);
        $eleves = $stmt->fetchAll();

        $nb_envois = 0;
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';       
        $mail->SMTPAuth   = true;
        $mail->Username   = 'votre-mail@gmail.com'; 
        $mail->Password   = 'mot_de_passe';      
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

            $mail->setFrom('vous@gmail.com', 'Équipe pédagogique');

            foreach ($eleves as $eleve) {
                $to = $eleve['email_parent'];
                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $mail->clearAddresses();
                    $mail->addAddress($to);
                    $mail->isHTML(true);
                    $mail->Subject = $objet;
                    $mail->Body = "Bonjour,

"
                        . "Message de suivi concernant votre enfant : " . $eleve['nom'] . " " . $eleve['prenom'] . "

"
                        . $message . "

"
                        . "Merci de votre collaboration.
L'équipe pédagogique.";
                    $mail->send();
                    $nb_envois++;
                }
            }

            $success = "✅ Messages envoyés à $nb_envois parent(s).";
        } catch (Exception $e) {
            $error = "Erreur lors de l'envoi : " . $mail->ErrorInfo;
        }
    } else {
        $error = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi des Élèves</title>
    <link rel="stylesheet" href="../assets/css/suivi.css">
</head>
<body>
<a href="dashboard.php" class="btn">⬅️ Retour</a>
<a href="suivi_eleves.php" class="btn">Pour la classe</a>
<a href="suivi_personnel.php" class="btn">Par élève</a>
<h2>📩 Message aux Parents</h2>

<?php if (!empty($success)): ?>
    <div class="msg success"><?= $success ?></div>
<?php elseif (!empty($error)): ?>
    <div class="msg error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <label>Classe :
        <select name="classe" required>
            <option value="">-- Choisir une classe --</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= htmlspecialchars($c['classe']) ?>"><?= htmlspecialchars($c['classe']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Objet :
        <input type="text" name="objet" required placeholder="Objet du message">
    </label>

    <label>Message :
        <textarea name="message" rows="6" required placeholder="Message à envoyer..."></textarea>
    </label>

    <input type="submit" name="send_message" value="Envoyer aux parents">
</form>
</body>
</html>
