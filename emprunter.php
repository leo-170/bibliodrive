<?php
session_start();

// Verifier connexion
if (!isset($_SESSION['mel'])) {
    header("Location: connexion.php");
    exit;
}

// Initialiser panier si necessaire
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Recuperer le livre a ajouter
if (isset($_GET['nolivre'])) {
    $nolivre = intval($_GET['nolivre']);

    // Ajoute au panier si pas deja dedans
    if (!in_array($nolivre, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $nolivre;
    }
}

header("Location: panier.php");
exit;
