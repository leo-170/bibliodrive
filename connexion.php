<?php
session_start();
ob_start();

$deco = $_GET['deco'] ?? null;
if ($deco == 1) {
    session_unset();
    session_destroy();
    header("Location: page_accueil.php");
    exit();
}

// Vérification si utilisateur est déjà connecté
if (isset($_SESSION["mel"])) {
    $utilisateurConnecte = true;
} else {
    $utilisateurConnecte = false;
}

// Si formulaire soumis
if (!$utilisateurConnecte && isset($_POST['btnconnexion'])) {
    $mel = $_POST['mel'];
    $motdepasse = $_POST['motdepasse'];

    // Hardcodé pour test
    if ($mel === "louis.martin@rabelais.com" && $motdepasse === "SECRET") {
        $_SESSION["mel"] = $mel;
        $_SESSION["prenom"] = "Louis";
        $_SESSION["nom"] = "Martin";
        $_SESSION["adresse"] = "1 Rue Exemple";
        $_SESSION["codepostal"] = "37000";
        $_SESSION["ville"] = "Tours";
        $_SESSION["profil"] = "client";

        header("Location: page_accueil.php");
        exit();
    } else {
        $erreur = "Échec de la connexion. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($utilisateurConnecte): ?>
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h3>Vous êtes connecté</h3>
                        <p><?= htmlspecialchars($_SESSION["prenom"] . " " . $_SESSION["nom"]) ?></p>
                        <a href="connexion.php?deco=1" class="btn btn-danger">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Connexion</h3>
                        <?php if (!empty($erreur)) echo '<div class="alert alert-danger">'.$erreur.'</div>'; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="mel" class="form-label">Votre mail :</label>
                                <input name="mel" id="mel" class="form-control" type="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="motdepasse" class="form-label">Votre mot de passe :</label>
                                <input name="motdepasse" id="motdepasse" class="form-control" type="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="btnconnexion" class="btn btn-success w-100">Connexion</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

<?php ob_end_flush(); ?>
