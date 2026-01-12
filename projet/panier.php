<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

//  si membre connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit;
}

$mel = $_SESSION['utilisateur']['mel'];


if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}


if (isset($_GET['supprimer'])) {
    $nolivre = intval($_GET['supprimer']);
    if (($key = array_search($nolivre, $_SESSION['panier'])) !== false) {
        unset($_SESSION['panier'][$key]);
    }
    header("Location: panier.php");
    exit;
}


if (isset($_GET['valider'])) {
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM emprunter WHERE mel = :mel AND dateretour IS NULL");
    $stmt->execute(['mel' => $mel]);
    $empruntsEnCours = $stmt->fetchColumn();

    $totalEmprunts = $empruntsEnCours + count($_SESSION['panier']);
    if ($totalEmprunts > 5) {
        $erreur = "Vous ne pouvez pas emprunter plus de 5 livres à la fois.";
    } else {
        foreach ($_SESSION['panier'] as $nolivre) {
            $stmt = $pdo->prepare("INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, CURDATE())");
            $stmt->execute(['mel'=>$mel, 'nolivre'=>$nolivre]);
        }
        $_SESSION['panier'] = [];
        $message = "Votre panier a été validé avec succès.";
    }
}


$livresPanier = [];
if (!empty($_SESSION['panier'])) {
    $in = str_repeat('?,', count($_SESSION['panier'])-1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM livre WHERE nolivre IN ($in)");
    $stmt->execute($_SESSION['panier']);
    $livresPanier = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon panier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Mon panier</h2>
    <?php if(isset($erreur)) echo "<div class='alert alert-danger'>$erreur</div>"; ?>
    <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

    <?php if(empty($livresPanier)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Année</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($livresPanier as $livre): ?>
                <tr>
                    <td><?= htmlspecialchars($livre['titre']) ?></td>
                    <td>
                        <?php
                        $stmt = $pdo->prepare("SELECT prenom, nom FROM auteur WHERE noauteur = ?");
                        $stmt->execute([$livre['noauteur']]);
                        $auteur = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo htmlspecialchars($auteur['prenom'] . " " . $auteur['nom']);
                        ?>
                    </td>
                    <td><?= $livre['anneeparution'] ?></td>
                    <td>
                        <a href="panier.php?supprimer=<?= $livre['nolivre'] ?>" class="btn btn-danger btn-sm">Annuler</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="panier.php?valider=1" class="btn btn-success">Valider le panier</a>
    <?php endif; ?>
</div>
</body>
</html>
