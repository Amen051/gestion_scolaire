<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

if (isset($_POST['lu_id'])) {
    $id = intval($_POST['lu_id']);
    $stmt = $pdo->prepare("UPDATE messages_visiteurs SET lu = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: messages.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM messages_visiteurs WHERE lu = 0 ORDER BY date_envoi DESC");
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages Internautes - Admin</title>
    <link rel="stylesheet" href="../assets/css/messages.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-container">
    <h1>🔔 Messages des visiteurs</h1>
    <p class="subtitle">Vous avez <?= count($messages) ?> nouveau(x) message(s) non lu(s).</p>

    <div class="msg-grid">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="msg-card">
                    <div class="msg-header">
                        <span class="user-info">📧 <?= htmlspecialchars($msg['email']) ?></span>
                        <span class="user-info">📞 <?= htmlspecialchars($msg['contact'] ?: 'Non renseigné') ?></span>
                    </div>
                    
                    <div class="msg-body">
                        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    </div>

                    <div class="msg-footer">
                        <div class="date">Reçu le : <?= date("d/m/Y à H:i", strtotime($msg['date_envoi'])) ?></div>
                        <form method="POST">
                            <input type="hidden" name="lu_id" value="<?= $msg['id'] ?>">
                            <button type="submit" class="btn-lu">✓ Lu</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-msg">
                <img src="../assets/img/empty-inbox.png" alt="Vide" style="width: 80px; opacity: 0.5;">
                <p>Aucun nouveau message pour l'instant ! 🎉</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="back-link">
        <a href="dashboard.php">← Retour au Dashboard</a>
    </div>
</div>

</body>
</html>