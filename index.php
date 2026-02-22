<?php
require_once 'includes/db.php';

$stmt_news = $pdo->query("SELECT * FROM actualites WHERE statut = 'public' ORDER BY date_publication DESC LIMIT 3");
$actus = $stmt_news->fetchAll();

$msg_success = "";
$msg_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer_message'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contact = htmlspecialchars(trim($_POST['contact']));
    $message = trim($_POST['message']);

    if (empty($email) || empty($message)) {
        $msg_error = "L'email et le message sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg_error = "L'adresse email n'est pas valide.";
    } else {
        $ins = $pdo->prepare("INSERT INTO messages_visiteurs (email, contact, message) VALUES (?, ?, ?)");
        if ($ins->execute([$email, $contact, $message])) {
            $msg_success = "Votre message a été envoyé avec succès !";
            $_POST = array(); 
        } else {
            $msg_error = "Une erreur technique est survenue.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.S.B JESUS SAUVE | Excellence & Discipline</title>
    <link rel="stylesheet" href="assets/css/vitrine.css?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="assets/img/logo.png" alt="Logo">
            <span>CSB JESUS SAUVE</span>
        </div>
        <ul class="nav-links">
            <li><a href="#accueil">Accueil</a></li>
            <li><a href="#actualites">Actualités</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="personnel.php" class="btn-login">Espace Personnel</a></li>
        </ul>
    </nav>

    <header id="accueil" class="hero">
        <div class="hero-overlay">
            <h1>Éduquer pour l'Excellence</h1>
            <p>Le Complexe Scolaire Baptiste Jésus Sauve prépare vos enfants aux défis de demain.</p>
            <a href="#actualites" class="btn-main">Voir les actualités</a>
        </div>
    </header>

    <section id="actualites" class="container">
        <div class="section-header">
            <h2>Dernières Actualités</h2>
            <hr>
        </div>
        <div class="news-grid">
            <?php if (!empty($actus)): ?>
                <?php foreach ($actus as $actu): ?>
                    <article class="news-card">
                        <div class="news-img" style="background-image: url('assets/img/actus/<?= $actu['image_url'] ?: 'default.jpg' ?>');"></div>
                        <div class="news-content">
                            <span class="date"><?= date('d M Y', strtotime($actu['date_publication'])) ?></span>
                            <h3><?= htmlspecialchars($actu['titre']) ?></h3>
                            <p><?= substr(htmlspecialchars($actu['contenu']), 0, 120) ?>...</p>
                            <a href="actu_detail.php?id=<?= $actu['id'] ?>" class="read-more">Lire la suite →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-news">Aucune actualité disponible.</div>
            <?php endif; ?>
        </div>
    </section>

    <section id="contact" class="bg-light">
        <div class="container">
            <div class="section-header">
                <h2>Contactez-nous</h2>
                <hr>
            </div>

            <?php if ($msg_success): ?>
                <div class="alert-success"><?= $msg_success ?></div>
            <?php endif; ?>

            <?php if ($msg_error): ?>
                <div class="alert-error"><?= $msg_error ?></div>
            <?php endif; ?>

            <div class="contact-wrapper">
                <form action="#contact" method="POST" class="contact-form">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Votre Email" required 
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        
                       <input type="tel" id="phone" name="contact_brut" placeholder="Téléphone / WhatsApp"
       						value="<?= isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : '' ?>">

						<input type="hidden" name="contact" id="full_phone">
                    </div>
                    <textarea name="message" rows="6" placeholder="Votre message ou question..." required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                    
                    <button type="submit" name="envoyer_message" class="btn-submit">Envoyer mon message</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-content">
            <p><strong>C.S.B JESUS SAUVE</strong> - LOMÉ, TOGO</p>
            <p>Direction : +228 90 13 50 89 | Secrétariat : +228 99 46 23 74</p>
            <div class="copyright">
                &copy; <?= date('Y') ?> CSBJS.
            </div>
        </div>
    </footer>
    <script>
    const phoneInputField = document.querySelector("#phone");
    const fullPhoneInput = document.querySelector("#full_phone");

    const phoneInput = window.intlTelInput(phoneInputField, {
        
        initialCountry: "tg",
        preferredCountries: ["tg", "bj", "ci", "gh"], 
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });

    document.querySelector(".contact-form").addEventListener("submit", function() {
        fullPhoneInput.value = phoneInput.getNumber();
    });
</script>
</body>
</html>