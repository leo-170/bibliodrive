<?php
session_start(); // Démarre la session PHP
require "config.php"; // Inclut la configuration pour la connexion PDO

// Vérifie si l'utilisateur est admin
if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    header("Location: page_accueil.php?msg=acces_interdit"); // Redirige si pas admin
    exit;
}

$erreurs = []; // Tableau pour stocker les messages d'erreur

// Récupère tous les auteurs pour le <select> dans le formulaire
$stmtAuteurs = $pdo->query("SELECT * FROM auteur ORDER BY nom, prenom");
$auteurs = $stmtAuteurs->fetchAll(PDO::FETCH_ASSOC); // Stocke les auteurs dans un tableau associatif

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère et nettoie les données du formulaire
    $titre = trim($_POST['titre'] ?? ''); // Supprime les espaces en début/fin de chaîne
    $noauteur = intval($_POST['auteur'] ?? 0); // Force la valeur en entier
    $isbn = trim($_POST['isbn'] ?? ''); // Récupère l'ISBN
    $anneeparution = intval($_POST['anneeparution'] ?? 0); // Année de parution
    $resume = trim($_POST['resume'] ?? ''); // Récupère le résumé

    // Validations simples
    if ($titre === '') $erreurs[] = "Le titre est obligatoire."; 
    if ($noauteur <= 0) $erreurs[] = "Vous devez choisir un auteur.";
    if ($isbn === '') $erreurs[] = "L'ISBN est obligatoire.";
    if ($anneeparution <= 0) $erreurs[] = "L'année de parution est obligatoire.";
    if ($resume === '') $erreurs[] = "Le résumé est obligatoire.";

    // Si aucune erreur
    if (empty($erreurs)) {
        // Prépare la requête SQL pour insérer le livre
        $stmt = $pdo->prepare("
            INSERT INTO livre (titre, noauteur, isbn13, anneeparution, detail, photo, dateajout)
            VALUES (:titre, :noauteur, :isbn, :anneeparution, :resume, NULL, CURDATE())
        ");
        // Exécute la requête avec les valeurs récupérées
        $stmt->execute([
            'titre' => $titre,
            'noauteur' => $noauteur,
            'isbn' => $isbn,
            'anneeparution' => $anneeparution,
            'resume' => $resume
        ]);

        // Redirection après ajout
        header("Location: page_accueil.php?msg=livre_ajoute");
        exit;
    }
}

// Inclut l'entête HTML
require "entete.php";
?>

<div class="container mt-4">
    <h2>Ajouter un livre</h2>

    
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
                <option value="">Choisir un auteur</option>
                <?php foreach($auteurs as $a): ?>
                    <option value="<?= $a['noauteur'] ?>">
                        <?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?> 
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        
        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label>Année de parution</label>
            <input type="number" name="anneeparution" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label>Résumé</label>
            <textarea name="resume" class="form-control" rows="5" required></textarea>
        </div>

        
        <button type="submit" class="btn btn-primary">Ajouter</button> 
    </form>
</div>
