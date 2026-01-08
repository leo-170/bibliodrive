<?php
session_start();

// Vérifier connexion
if (!isset($_SESSION['mel'])) {
    header("Location: connexion.php");
    exit;
}

// Initialiser panier si nécessaire
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Récupérer le livre à ajouter
if (isset($_GET['nolivre'])) {
    $nolivre = intval($_GET['nolivre']);

    // Ajoute au panier si pas déjà dedans
    if (!in_array($nolivre, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $nolivre;
    }
}

header("Location: panier.php");
exit;
