<?php
session_start();
require "config.php";


if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    header("Location: page_accueil.php?msg=acces_interdit");
    exit;
}

$erreurs = [];


$stmtAuteurs = $pdo->query("SELECT * FROM auteur ORDER BY nom, prenom");
$auteurs = $stmtAuteurs->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $noauteur = intval($_POST['auteur'] ?? 0);

    if ($titre === '') $erreurs[] = "Le titre est obligatoire.";
    if ($noauteur <= 0) $erreurs[] = "Vous devez choisir un auteur.";

    if (empty($erreurs)) {
        
        $stmt = $pdo->prepare("
            INSERT INTO livre (titre, noauteur, photo, dateajout)
            VALUES (:titre, :noauteur, NULL, CURDATE())
        ");
        $stmt->execute([
            'titre' => $titre,
            'noauteur' => $noauteur
        ]);

        header("Location: page_accueil.php?msg=livre_ajoute");
        exit;
    }
}

require "entete.php";
?>

<div class="container mt-4">
    <h2>Ajouter un livre</h2>

    <p class="text-warning">⚠️ L'ajout de photo n'est plus disponible.</p>

    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?php foreach ($erreurs as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Auteur</label>
            <select name="auteur" class="form-control" required>
                <option value="">-- Choisir --</option>
                <?php foreach($auteurs as $a): ?>
                    <option value="<?= $a['noauteur'] ?>">
                        <?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
