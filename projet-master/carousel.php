<?php
// Connexion SQL
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// Récupération des 3 derniers livres
$stmt = $pdo->query("SELECT photo FROM livre ORDER BY dateajout DESC LIMIT 3");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="demo" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicateurs -->
  <div class="carousel-indicators">
    <?php foreach ($livres as $index => $livre): ?>
      <button type="button"
              data-bs-target="#demo"
              data-bs-slide-to="<?= $index ?>"
              class="<?= $index === 0 ? 'active' : '' ?>"></button>
    <?php endforeach; ?>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">
    <?php foreach ($livres as $index => $livre): ?>
      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
        <img src="covers/<?= htmlspecialchars($livre['photo']) ?>" 
             class="d-block mx-auto" 
             style="width:20%;">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Boutons de navigation -->
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Précédent</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Suivant</span>
  </button>

</div>
