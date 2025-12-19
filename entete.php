<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>BIBLIO DRIVE</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid mt-3 text-center">
    <h5>C’EST FERMÉ ! GRÂCE À BIBLIODRIVE, TU PEUX ACCÉDER AUX LIVRES</h5>
</div>
<br>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="page_accueil.php">Bibliodrive</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="page_panier.php">Panier</a>
          </li>
        </ul>

        <!-- Formulaire de recherche -->
        <form class="d-flex me-3" method="GET" action="recherche.php">
          <input class="form-control me-2" type="text" name="q" placeholder="Rechercher un auteur">
          <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <!-- Bouton Connexion / Déconnexion -->
        <?php if (isset($_SESSION['mel'])): ?>
            <a class="btn btn-success" href="connexion.php?deco=1">
                Déconnexion (<?= htmlspecialchars($_SESSION['prenom']) ?>)
            </a>
        <?php else: ?>
            <a class="btn btn-warning" href="connexion.php">
                Connexion
            </a>
        <?php endif; ?>
      </div>
    </div>
</nav>
