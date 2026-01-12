<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// verifier si admin
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['profil'] !== 'admin') {
    die("Accès refusé. Cette page est réservée aux administrateurs.");
}
?>

 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $noauteur = intval($_POST['noauteur']);
    $isbn = trim($_POST['isbn']);
    $annee = intval($_POST['anneeparution']);
    $resume = trim($_POST['resume']);
    $photo = trim($_POST['photo']); 

    $stmt = $pdo->prepare("INSERT INTO livre (noauteur, titre, isbn13, anneeparution, resume, dateajout, photo)
                           VALUES (?, ?, ?, ?, ?, CURDATE(), ?)");
    $stmt->execute([$noauteur, $titre, $isbn, $annee, $resume, $photo]);
    $message = "Livre ajouté avec succès.";
}


$auteurs = $pdo->query("SELECT * FROM auteur")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter un livre</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Ajouter un livre</h2>
    <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label>Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Auteur</label>
            <select name="noauteur" class="form-select" required>
                <?php foreach($auteurs as $auteur): ?>
                <option value="<?= $auteur['noauteur'] ?>"><?= htmlspecialchars($auteur['prenom'] . " " . $auteur['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Année parution</label>
            <input type="number" name="anneeparution" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Résumé</label>
            <textarea name="resume" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Nom fichier image (dans covers/)</label>
            <input type="text" name="photo" class="form-control">
        </div>
        <button class="btn btn-primary">Ajouter</button>
    </form>
</div>
</body>
</html>
