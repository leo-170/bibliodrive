<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// Vérifier si connecté
if (!isset($_SESSION["mel"])) {
    echo "<p>Connectez-vous pour voir votre panier.</p>";
    exit;
}

// Initialisation panier si inexistant
if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [];
}

// --- AJOUTER AU PANIER ---
if (isset($_GET['ajouter'])) {
    $nolivre = intval($_GET['ajouter']);

    if (!in_array($nolivre, $_SESSION["panier"])) {

        // Vérifier si pas déjà emprunté par ce membre
        $stmtCheck = $pdo->prepare("
            SELECT * FROM emprunter 
            WHERE nolivre = :nolivre 
              AND mel = :mel 
              AND dateretour IS NULL
        ");
        $stmtCheck->execute([
            'nolivre' => $nolivre,
            'mel'     => $_SESSION["mel"]
        ]);

        if ($stmtCheck->rowCount() == 0) {
            $_SESSION["panier"][] = $nolivre;
        }
    }

    header("Location: panier.php");
    exit;
}

// --- SUPPRIMER DU PANIER ---
if (isset($_GET['supprimer'])) {
    $nolivre = intval($_GET['supprimer']);
    $_SESSION["panier"] = array_diff($_SESSION["panier"], [$nolivre]);
    header("Location: panier.php");
    exit;
}

// --- VALIDATION PANIER ---
if (isset($_POST['valider'])) {

    // 1) compter les emprunts déjà en cours
    $stmtCount = $pdo->prepare("
        SELECT COUNT(*) FROM emprunter 
        WHERE mel = :mel AND dateretour IS NULL
    ");
    $stmtCount->execute(['mel' => $_SESSION["mel"]]);
    $empruntsEnCours = $stmtCount->fetchColumn();

    $nbPanier = count($_SESSION["panier"]);

    // Limite à 5 emprunts
    if ($empruntsEnCours + $nbPanier > 5) {
        echo "<p class='text-danger'>Vous ne pouvez pas emprunter plus de 5 livres en même temps.</p>";
    } else {
        foreach ($_SESSION["panier"] as $nolivre) {
            // Vérifier si déjà emprunté par ce membre
            $stmt_exist = $pdo->prepare("
                SELECT * FROM emprunter
                WHERE mel = :mel AND nolivre = :nolivre AND dateretour IS NULL
            ");
            $stmt_exist->execute([
                'mel'     => $_SESSION['mel'],
                'nolivre' => $nolivre
            ]);

            if ($stmt_exist->rowCount() == 0) {
                $stmt2 = $pdo->prepare("
                    INSERT INTO emprunter (mel, nolivre, dateemprunt)
                    VALUES (:mel, :nolivre, CURDATE())
                ");
                $stmt2->execute([
                    'mel'     => $_SESSION['mel'],
                    'nolivre' => $nolivre
                ]);
            }
        }

        // Vider panier
        $_SESSION["panier"] = [];

        // REDIRECTION vers la page principale après validation
        header("Location: page_accueil.php?msg=panier_valide");
        exit;
    }
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
            <td>
                <a href="panier.php?supprimer=<?= $nolivre ?>" 
                   class="btn btn-danger btn-sm">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <form method="post">
        <button type="submit" name="valider" class="btn btn-success">
            Valider le panier
        </button>
    </form>
<?php endif; ?>
