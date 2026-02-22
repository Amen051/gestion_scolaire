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

$eleves = [];

if (isset($_POST['classe'])) {
    $classe = $_POST['classe'];
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email_parent FROM eleves WHERE classe = ?");
    $stmt->execute([$classe]);
    $eleves = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $objet = trim($_POST['objet']);
    $message = trim($_POST['message']);
    $classe = $_POST['classe'];
    $eleve_ids = $_POST['eleves'] ?? [];

    if (!empty($classe) && !empty($objet) && !empty($message) && !empty($eleve_ids)) {
        $placeholders = implode(',', array_fill(0, count($eleve_ids), '?'));
        $stmt = $pdo->prepare("SELECT nom, prenom, email_parent FROM eleves WHERE id IN ($placeholders)");
        $stmt->execute($eleve_ids);
        $selected_eleves = $stmt->fetchAll();

        $mail = new PHPMailer(true);
        $nb_envois = 0;

        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mail@gmail.com';
            $mail->Password   = 'mot_de-passe'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('toi@.gmail.com', 'Equipe pedagogique');

            foreach ($selected_eleves as $eleve) {
                $to = $eleve['email_parent'];
                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $mail->clearAddresses();
                    $mail->addAddress($to);
                    $mail->Subject = $objet;
                    $mail->isHTML(true);

$mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
        }
        .header {
            display: flex;
            align-items: center;
            background: #007BFF;
            color: white;
            padding: 15px;
            border-radius: 6px 6px 0 0;
        }
        .header img {
            height: 50px;
            margin-right: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .highlight {
            font-weight: bold;
            color: #007BFF;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #555;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Suivi pédagogique de votre enfant</h1>
        </div>

        <p>Bonjour,</p>

        <p>Nous souhaitons vous informer au sujet de <span class="highlight">' . htmlspecialchars($eleve['nom']) . ' ' . htmlspecialchars($eleve['prenom']) . '</span>, actuellement en classe de <strong>' . htmlspecialchars($classe) . '</strong>.</p>

        <p>' . nl2br(htmlspecialchars($message)) . '</p>

        <p>Nous restons à votre disposition pour toute question ou précision.</p>

        <div class="footer">
            Cordialement,<br>
            <strong>Équipe pédagogique</strong><br>
            CSBJS Bé<br>
            📧 <a href="mailto:dbdf@gmail.com">sdbs5@gmail.com</a>
        </div>
    </div>
</body>
</html>';

                    $mail->send();
                    $nb_envois++;
                }
            }

            $success = "✅ Messages envoyés à $nb_envois parent(s).";
        } catch (Exception $e) {
            $error = "❌ Erreur d'envoi : " . $mail->ErrorInfo;
        }
    } else {
        $error = "⚠️ Veuillez remplir tous les champs et sélectionner au moins un élève.";
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
<h2>📩 Message aux Parents</h2>

<?php if (!empty($success)): ?>
    <div class="msg success"><?= $success ?></div>
<?php elseif (!empty($error)): ?>
    <div class="msg error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <label>Classe :
        <select name="classe" required onchange="this.form.submit()">
            <option value="">-- Choisir une classe --</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= $c['classe'] ?>" <?= (isset($_POST['classe']) && $_POST['classe'] == $c['classe']) ? 'selected' : '' ?>>
                    <?= $c['classe'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <?php if (!empty($eleves)): ?>
        <fieldset>
            <legend>✅ Sélectionner les élèves concernés :</legend>
            <?php foreach ($eleves as $e): ?>
                <label style="display: block;">
                    <input type="checkbox" name="eleves[]" value="<?= $e['id'] ?>">
                    <?= htmlspecialchars($e['nom']) . " " . htmlspecialchars($e['prenom']) . " (" . $e['email_parent'] . ")" ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
    <?php endif; ?>

    <label>Objet :
        <input type="text" name="objet" required placeholder="Objet du message">
    </label>

    <label>Message :
        <textarea name="message" rows="6" required placeholder="Message à envoyer..."></textarea>
    </label>

    <input type="submit" name="send_message" value="📤 Envoyer aux parents">
</form>
</body>
</html>
