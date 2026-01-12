<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['profil'] !== 'admin') {
    die("Accès refusé. Cette page est réservée aux administrateurs.");
}
?>

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mel = trim($_POST['mel']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $adresse = trim($_POST['adresse']);
    $ville = trim($_POST['ville']);
    $cp = intval($_POST['codepostal']);
    $profil = trim($_POST['profil']);
    $mdp = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$mel,$mdp,$nom,$prenom,$adresse,$ville,$cp,$profil]);
    $message = "Membre ajouté avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Créer un membre</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Créer un membre</h2>
    <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
    <form method="post">
        <div class="mb-3"><label>Email</label><input type="email" name="mel" class="form-control" required></div>
        <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
        <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
        <div class="mb-3"><label>Adresse</label><input type="text" name="adresse" class="form-control" required></div>
        <div class="mb-3"><label>Ville</label><input type="text" name="ville" class="form-control" required></div>
        <div class="mb-3"><label>Code postal</label><input type="number" name="codepostal" class="form-control" required></div>
        <div class="mb-3"><label>Profil</label>
            <select name="profil" class="form-select">
                <option value="membre">Membre</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <div class="mb-3"><label>Mot de passe</label><input type="password" name="motdepasse" class="form-control" required></div>
        <button class="btn btn-primary">Créer</button>
    </form>
</div>
</body>
</html>
