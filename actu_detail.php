<?php
require_once 'includes/db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM actualites WHERE id = ? AND statut = 'public'");
    $stmt->execute([$id]);
    $actu = $stmt->fetch();

    if (!$actu) {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($actu['titre']) ?> | CSBJS</title>
    <link rel="stylesheet" href="assets/css/vitrine.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo"><span>CSB JESUS SAUVE</span></div>
        <ul class="nav-links">
            <li><a href="index.php">Retour à l'accueil</a></li>
        </ul>
    </nav>

    <div class="container" style="margin-top: 50px;">
        <article class="full-article">
            <h1><?= htmlspecialchars($actu['titre']) ?></h1>
            <p class="date">Publié le <?= date('d/m/Y', strtotime($actu['date_publication'])) ?></p>
            
            <?php if ($actu['image_url']): ?>
                <img src="assets/img/actus/<?= $actu['image_url'] ?>" alt="" style="width:100%; max-height:400px; object-fit:cover; border-radius:10px; margin: 20px 0;">
            <?php endif; ?>

            <div class="content" style="font-size: 1.2rem; line-height: 1.8; white-space: pre-wrap;">
                <?= htmlspecialchars($actu['contenu']) ?>
            </div>
        </article>
    </div>
</body>
</html>