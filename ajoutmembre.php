<?php
session_start(); // démarre la session
require "config.php"; // inclut config PDO

// vérifie si l'utilisateur est admin
if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    header("Location: page_accueil.php?msg=acces_interdit"); // redirige si pas admin
    exit;
}

$erreurs = []; // tableau pour stocker erreurs

// si formulaire envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // récupération et nettoyage des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $mel = trim($_POST['mel'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';
    $profil = $_POST['profil'] ?? 'membre';

    // nouveaux champs adresse, ville, code postal
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $codepostal = trim($_POST['codepostal'] ?? '');

    // validations simples
    if ($nom === '') $erreurs[] = "Le nom est obligatoire.";
    if ($prenom === '') $erreurs[] = "Le prénom est obligatoire.";
    if ($mel === '') $erreurs[] = "L'email est obligatoire.";
    if (!filter_var($mel, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";
    if ($motdepasse === '') $erreurs[] = "Le mot de passe est obligatoire.";
    if ($adresse === '') $erreurs[] = "L'adresse est obligatoire.";
    if ($ville === '') $erreurs[] = "La ville est obligatoire.";
    if ($codepostal === '') $erreurs[] = "Le code postal est obligatoire.";

    // vérifie si email déjà utilisé
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE mel = ?");
    $stmt->execute([$mel]);
    if ($stmt->rowCount() > 0) {
        $erreurs[] = "Cet email est déjà utilisé.";
    }

    // si pas d'erreurs
    if (empty($erreurs)) {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT); // hash du mot de passe
        $stmt = $pdo->prepare("
            INSERT INTO utilisateur 
            (nom, prenom, mel, motdepasse, profil, adresse, ville, codepostal)
            VALUES 
            (:nom, :prenom, :mel, :motdepasse, :profil, :adresse, :ville, :codepostal)
        ");
        $stmt->execute([ // exécute l'insertion avec valeurs
            'nom' => $nom,
            'prenom' => $prenom,
            'mel' => $mel,
            'motdepasse' => $hash,
            'profil' => $profil,
            'adresse' => $adresse,
            'ville' => $ville,
            'codepostal' => $codepostal
        ]);

        header("Location: page_accueil.php?msg=membre_ajoute"); // redirection après ajout
        exit;
    }
}

require "entete.php"; // inclut l'entête HTML
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

    
    <div class="mb-3">
        <label>Adresse</label>
        <input type="text" name="adresse" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Ville</label>
        <input type="text" name="ville" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Code postal</label>
        <input type="text" name="codepostal" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php require "footer.php"; ?>
