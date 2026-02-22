<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login/login_admin.php');
    exit();
}
require_once '../includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publier'])) {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $statut = $_POST['statut'];
    $image_name = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp','avif'];
        $file_info = pathinfo($_FILES['image']['name']);
        $extension = strtolower($file_info['extension']);

        if (in_array($extension, $allowed_extensions)) {
           
            $image_name = time() . '_' . uniqid() . '.' . $extension;
            $target_path = "../assets/img/actus/" . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $message = "<p style='color:red;'>Erreur lors du déplacement de l'image.</p>";
            }
        } else {
            $message = "<p style='color:red;'>Format d'image non autorisé (JPG,JPEG, PNG, WEBP et AVIF uniquement).</p>";
        }
    }

    if (empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image_url, statut) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$titre, $contenu, $image_name, $statut])) {
            $message = "<p style='color:green;'>Actualité publiée avec succès !</p>";
        } else {
            $message = "<p style='color:red;'>Erreur lors de l'enregistrement en base de données.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Actualité</title>
    <link rel="stylesheet" href="../assets/css/admin_forms.css">
</head>
<body>

<div class="form-container">
       <a href="dashboard.php" class="btn">⬅️ Retour à l'espace admin</a>
    <h1>📢 Publier une actualité</h1>
    <?= $message ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="field">
            <label>Titre de l'actualité</label>
            <input type="text" name="titre" required placeholder="Ex: Réunion des parents...">
        </div>

        <div class="field">
            <label>Contenu</label>
            <textarea name="contenu" rows="8" required placeholder="Décrivez l'événement ici..."></textarea>
        </div>

        <div class="field">
            <label>Image d'illustration (Optionnel)</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="field">
            <label>Statut de publication</label>
            <select name="statut">
                <option value="public">🚀 Public (S'affiche sur le site)</option>
                <option value="brouillon">📝 Brouillon (Caché)</option>
            </select>
        </div>

        <div class="actions">
            <button type="submit" name="publier" class="btn-save">Enregistrer l'actualité</button>
            <a href="gestion_actus.php" class="btn-back">Liste des actualités</a>
        </div>
    </form>
</div>

</body>
</html>