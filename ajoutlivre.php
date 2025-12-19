<?php
require "config.php";

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['profil'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $auteur = intval($_POST['auteur']);
    $photo = $_FILES['photo']['name'];

    move_uploaded_file($_FILES['photo']['tmp_name'], "images/$photo");

    $stmt = $pdo->prepare("INSERT INTO livre (titre, noauteur, photo, dateajout) VALUES (:titre, :auteur, :photo, NOW())");
    $stmt->execute(['titre' => $titre, 'auteur' => $auteur, 'photo' => $photo]);
    header("Location: index.php");
    exit;
}

require "entete.php";

// Récupérer les auteurs
$auteurs = $pdo->query("SELECT * FROM auteur")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Ajouter un livre</h2>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Titre</label>
        <input type="text" name="titre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Auteur</label>
        <select name="auteur" class="form-control" required>
            <?php foreach($auteurs as $a): ?>
            <option value="<?= $a['noauteur'] ?>"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Photo</label>
        <input type="file" name="photo" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php require "footer.php"; ?>
