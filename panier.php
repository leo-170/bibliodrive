<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// Vérifie connexion
if (!isset($_SESSION["mel"])) {
    echo "<p>Connectez-vous pour voir votre panier.</p>";
    exit;
}

// Initialisation du panier si inexistant
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

// Ajouter un livre au panier
if (isset($_GET['ajouter'])) {
    $nolivre = intval($_GET['ajouter']);

    // Vérifie si déjà dans le panier
    if (!in_array($nolivre, $_SESSION["panier"])) {
        $_SESSION["panier"][] = $nolivre;
    }
    header("Location: panier.php");
    exit;
}

// Supprimer un livre du panier
if (isset($_GET['supprimer'])) {
    $nolivre = intval($_GET['supprimer']);
    $_SESSION["panier"] = array_diff($_SESSION["panier"], [$nolivre]);
    header("Location: panier.php");
    exit;
}

// Valider le panier (enregistrer les emprunts)
if (isset($_POST['valider'])) {
    foreach ($_SESSION["panier"] as $nolivre) {
        $stmt = $pdo->prepare("SELECT * FROM emprunter WHERE nolivre = :nolivre AND dateretour IS NULL");
        $stmt->execute(['nolivre' => $nolivre]);
        if ($stmt->rowCount() == 0) {
            $stmt2 = $pdo->prepare("INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, CURDATE())");
            $stmt2->execute(['mel' => $_SESSION["mel"], 'nolivre' => $nolivre]);
        }
    }
    $_SESSION["panier"] = []; // Vide le panier après validation
    echo "<p>Panier validé avec succès !</p>";
}

?>
<h1>Votre Panier</h1>

<?php if (empty($_SESSION["panier"])): ?>
    <p>Votre panier est vide.</p>
<?php else: ?>
    <table class="table table-bordered">
        <tr>
            <th>Titre</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($_SESSION["panier"] as $nolivre):
            $stmt = $pdo->prepare("SELECT titre FROM livre WHERE nolivre = :nolivre");
            $stmt->execute(['nolivre' => $nolivre]);
            $livre = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <tr>
            <td><?= htmlspecialchars($livre['titre']) ?></td>
            <td><a href="panier.php?supprimer=<?= $nolivre ?>" class="btn btn-danger btn-sm">Supprimer</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <form method="post">
        <button type="submit" name="valider" class="btn btn-success">Valider le panier</button>
    </form>
<?php endif; ?>
