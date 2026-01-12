<?php
include "config.php"; // inclut fichier config PDO

// recupere les 3 derniers livres par date ajout
$stmt = $pdo->query("SELECT photo FROM livre ORDER BY dateajout DESC LIMIT 3");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC); // stocke resultats dans tableau
?>

<div id="demo" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php foreach ($livres as $index => $livre): ?>
      <button type="button" data-bs-target="#demo" data-bs-slide-to="<?= $index ?>" class="<?= $index===0?'active':'' ?>"></button> 
    <?php endforeach; ?>
  </div>
  <div class="carousel-inner">
    <?php foreach ($livres as $index => $livre): ?>
      <div class="carousel-item <?= $index===0?'active':'' ?>"> 
        <img src="covers/<?= htmlspecialchars($livre['photo']) ?>" class="d-block mx-auto" style="width:30%;" alt="Livre <?= $index+1 ?>"> 
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span> 
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span> 
  </button>
</div>
