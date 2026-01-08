<?php
session_start();
require_once "config.php"; 

$erreur = "";


if (isset($_GET['deco'])) {
    session_unset();
    session_destroy();
    header("Location: page_accueil.php");
    exit;
}


if (isset($_POST['btnconnexion'])) {

    $mel = $_POST['mel'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';

    
    $stmt = $pdo->prepare(
        "SELECT * FROM utilisateur WHERE mel = :mel"
    );
    $stmt->execute(['mel' => $mel]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user && password_verify($motdepasse, $user['motdepasse'])) {

        $_SESSION['mel'] = $user['mel'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['adresse'] = $user['adresse'];
        $_SESSION['ville'] = $user['ville'];
        $_SESSION['codepostal'] = $user['codepostal'];
        $_SESSION['profil'] = $user['profil'];

        header("Location: page_accueil.php");
        exit;

    } else {
        $erreur = "Identifiants incorrects";
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
        <div class="col-md-5">

            <?php if (isset($_SESSION['mel'])): ?>

                <div class="card shadow text-center">
                    <div class="card-body">
                        <h4>Connecté</h4>
                        <p><?= htmlspecialchars($_SESSION['prenom'].' '.$_SESSION['nom']) ?></p>
                        <a href="connexion.php?deco=1" class="btn btn-danger">Déconnexion</a>
                    </div>
                </div>

            <?php else: ?>

                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center mb-3">Connexion</h4>

                        <?php if ($erreur): ?>
                            <div class="alert alert-danger"><?= $erreur ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="mel" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="motdepasse" class="form-control" required>
                            </div>

                            <button type="submit" name="btnconnexion" class="btn btn-success w-100">
                                Connexion
                            </button>
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>
