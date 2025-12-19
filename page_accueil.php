<?php
include "config.php";
include "entete.php";

// 3 derniers livres pour le carousel
$stmt = $pdo->query("SELECT * FROM livre ORDER BY dateajout DESC LIMIT 3");
$livresCarousel = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tous les livres
$stmt = $pdo->query("SELECT livre.*, auteur.nom AS nomAuteur, auteur.prenom AS prenomAuteur
                     FROM livre
                     INNER JOIN auteur ON livre.noauteur = auteur.noauteur");
$tousLivres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="text-center mb-4">DERNIERS ARTICLES</h3>

<!-- Carousel -->
<div id="demo" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($livresCarousel as $index => $livre): ?>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="<?= $index ?>" class="<?= $index===0?'active':'' ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($livresCarousel as $index => $livre): ?>
            <div class="carousel-item <?= $index===0?'active':'' ?>">
                <img src="covers/<?= htmlspecialchars($livre['photo']) ?>" class="d-block mx-auto" style="height:300px;" alt="<?= htmlspecialchars($livre['titre']) ?>">
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

<h3 class="text-center mb-4">TOUS LES LIVRES</h3>
<div class="row">
<?php foreach ($tousLivres as $livre): ?>
    <div class="col-md-3 mb-3">
        <div class="card h-100 shadow-sm">
            <img src="covers/<?= htmlspecialchars($livre['photo']) ?>" class="card-img-top" style="height:250px;object-fit:cover;">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($livre['titre']) ?></h5>
                <p class="card-text"><strong>Auteur :</strong> <?= htmlspecialchars($livre['prenomAuteur'].' '.$livre['nomAuteur']) ?></p>
                <a href="livre.php?nolivre=<?= $livre['nolivre'] ?>" class="btn btn-primary w-100">Voir / Emprunter</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include "footer.php"; ?>
