<?php

$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");


$q = trim($_GET['q'] ?? '');

$resultats = [];

if ($q !== '') {

    $stmt = $pdo->prepare("
    SELECT livre.nolivre, livre.titre, livre.photo, livre.anneeparution, livre.isbn13, livre.detail,
           auteur.nom, auteur.prenom
    FROM livre
    INNER JOIN auteur ON livre.noauteur = auteur.noauteur
    WHERE auteur.nom LIKE :q
       OR auteur.prenom LIKE :q
       OR CONCAT(auteur.prenom, ' ', auteur.nom) LIKE :q
       OR CONCAT(auteur.nom, ' ', auteur.prenom) LIKE :q
");

    $stmt->execute(['q' => '%' . $q . '%']);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Résultats pour : <strong><?= htmlspecialchars($q) ?></strong></h2>
    <hr>

    <?php if (empty($resultats)): ?>
        <p>Aucun livre trouvé pour cet auteur.</p>
    <?php else: ?>

        <div class="row">

            <?php foreach ($resultats as $livre): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <?php if (!empty($livre['photo'])): ?>
                            <img src="covers/<?= htmlspecialchars($livre['photo']) ?>"
                                 class="card-img-top" alt="Photo livre">
                        <?php endif; ?>

                        <div class="card-body">
                        <h5 class="card-title">
                         <a href="livre.php?nolivre=<?= $livre['nolivre'] ?>">
                            <?= htmlspecialchars($livre['titre']) ?>
                             </a>
                        </h5>
                            <p class="card-text">
                                <strong>Auteur :</strong>
                                <?= htmlspecialchars($livre['prenom'] . " " . $livre['nom']) ?><br>
                                <strong>Année :</strong> <?= $livre['anneeparution'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
