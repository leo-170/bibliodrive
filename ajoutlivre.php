<?php
session_start(); // demarre la session
require "config.php"; // inclut le fichier config pour la connexion PDO

// verifie si utilisateur est admin
if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    header("Location: page_accueil.php?msg=acces_interdit"); // redirige si pas admin
    exit;
}

$erreurs = []; // tableau pour stocker les erreurs

// recupere tous les auteurs pour le select
$stmtAuteurs = $pdo->query("SELECT * FROM auteur ORDER BY nom, prenom");
$auteurs = $stmtAuteurs->fetchAll(PDO::FETCH_ASSOC); // stocke les auteurs dans un tableau

// si le formulaire est envoye
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? ''); // recupere le titre
    $noauteur = intval($_POST['auteur'] ?? 0); // recupere l'auteur et force entier

    // validations simples
    if ($titre === '') $erreurs[] = "Le titre est obligatoire."; 
    if ($noauteur <= 0) $erreurs[] = "Vous devez choisir un auteur.";

    // si pas d'erreurs
    if (empty($erreurs)) {
        // prepare la requete pour ajouter le livre
        $stmt = $pdo->prepare("
            INSERT INTO livre (titre, noauteur, photo, dateajout)
            VALUES (:titre, :noauteur, NULL, CURDATE())
        ");
        // execute la requete avec les valeurs
        $stmt->execute([
            'titre' => $titre,
            'noauteur' => $noauteur
        ]);

        header("Location: page_accueil.php?msg=livre_ajoute"); // redirige avec message
        exit;
    }
}

require "entete.php"; // inclut l'entete html
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
