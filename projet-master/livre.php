<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// Récupération du numéro de livre
$nolivre = isset($_GET['nolivre']) ? intval($_GET['nolivre']) : 0;

// Si pas de numéro → erreur
if ($nolivre <= 0) {
    die("Livre introuvable.");
}

// Récupérer les infos du livre
$stmt = $pdo->prepare("
    SELECT livre.*, auteur.nom AS nomAuteur, auteur.prenom AS prenomAuteur
    FROM livre
    INNER JOIN auteur ON livre.noauteur = auteur.noauteur
    WHERE livre.nolivre = :nolivre
");
$stmt->execute(['nolivre' => $nolivre]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier la disponibilité du livre
$stmt = $pdo->prepare("
    SELECT *
    FROM emprunter
    WHERE nolivre = :nolivre AND dateretour IS NULL
    LIMIT 1
");
$stmt->execute(['nolivre' => $nolivre]);
$emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

$disponible = ($emprunt === false);

if (!$livre) {
    die("Livre introuvable !");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($livre['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <a href="javascript:history.back();" class="btn btn-secondary mb-3">← Retour</a>

    <div class="card shadow">
        <div class="row g-0">

            <?php if ($livre['photo']): ?>
            <div class="col-md-4">
                <img src="covers/<?= htmlspecialchars($livre['photo']) ?>" class="img-fluid rounded-start">
            </div>
            <?php endif; ?>

            <div class="col-md-8">
                <div class="card-body">
                <p>
                     <strong>Disponibilité :</strong>
                        <?php if ($disponible): ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Indisponible</span><br>
                                    <small class="text-muted">Emprunté par : <?= htmlspecialchars($emprunt['mel']) ?></small>
                                <?php endif; ?>
                            </p>

                            <!-- Boutons Emprunter / Retourner -->
                            <?php if ($disponible): ?>
                                <a href="emprunter.php?nolivre=<?= $nolivre ?>" class="btn btn-primary">
                                    Emprunter ce livre
                                </a>
                            <?php else: ?>
                                <a href="retour.php?nolivre=<?= $nolivre ?>" class="btn btn-warning">
                                    Retourner ce livre
                                </a>
                            <?php endif; ?>
                    <h2 class="card-title"><?= htmlspecialchars($livre['titre']) ?></h2>

                    <p class="text-muted">
                        Auteur :
                        <strong><?= htmlspecialchars($livre['prenomAuteur'] . " " . $livre['nomAuteur']) ?></strong><br>
                        Année : <strong><?= $livre['anneeparution'] ?></strong><br>
                        ISBN : <strong><?= $livre['isbn13'] ?></strong><br>
                        Date ajout : <strong><?= $livre['dateajout'] ?></strong>
                    </p>

                    <hr>

                    <p><?= nl2br(htmlspecialchars($livre['detail'])) ?></p>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
