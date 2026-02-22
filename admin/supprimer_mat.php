<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM matieres WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: matiere.php");
exit();
