<?php
session_start();
require "config.php";


if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    header("Location: page_accueil.php?msg=acces_interdit");
    exit;
}

$erreurs = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $mel = trim($_POST['mel'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';
    $profil = $_POST['profil'] ?? 'membre'; 

    
    if ($nom === '') $erreurs[] = "Le nom est obligatoire.";
    if ($prenom === '') $erreurs[] = "Le prénom est obligatoire.";
    if ($mel === '') $erreurs[] = "L'email est obligatoire.";
    if (!filter_var($mel, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";
    if ($motdepasse === '') $erreurs[] = "Le mot de passe est obligatoire.";

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE mel = ?");
    $stmt->execute([$mel]);
    if ($stmt->rowCount() > 0) {
        $erreurs[] = "Cet email est déjà utilisé.";
    }

    if (empty($erreurs)) {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO utilisateur (nom, prenom, mel, motdepasse, profil)
            VALUES (:nom, :prenom, :mel, :motdepasse, :profil)
        ");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'mel' => $mel,
            'motdepasse' => $hash,
            'profil' => $profil
        ]);

        header("Location: page_accueil.php?msg=membre_ajoute");
        exit;
    }
}

require "entete.php";
?>

<h2>Ajouter un membre</h2>

<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <?php foreach ($erreurs as $e) echo "<p>$e</p>"; ?>
    </div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="nom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Prénom</label>
        <input type="text" name="prenom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="mel" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mot de passe</label>
        <input type="password" name="motdepasse" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Profil</label>
        <select name="profil" class="form-control">
            <option value="membre">Membre</option>
            <option value="admin">Administrateur</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php require "footer.php"; ?>
