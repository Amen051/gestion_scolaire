<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM professeurs WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: professeurs.php");
exit();
