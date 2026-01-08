<?php
include "config.php";
include "entete.php";

$nolivre = isset($_GET['nolivre']) ? intval($_GET['nolivre']) : 0;
if ($nolivre <= 0) die("Livre introuvable.");

// Infos livre
$stmt = $pdo->prepare("SELECT livre.*, auteur.nom AS nomAuteur, auteur.prenom AS prenomAuteur
                       FROM livre
                       INNER JOIN auteur ON livre.noauteur = auteur.noauteur
                       WHERE livre.nolivre = :nolivre");
$stmt->execute(['nolivre'=>$nolivre]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$livre) die("Livre introuvable !");

// Verifie disponibilite
$stmt = $pdo->prepare("SELECT * FROM emprunter WHERE nolivre = :nolivre AND dateretour IS NULL LIMIT 1");
$stmt->execute(['nolivre'=>$nolivre]);
$emprunt = $stmt->fetch(PDO::FETCH_ASSOC);
$disponible = ($emprunt === false);
?>

<div class="container mt-4">
    <a href="page_accueil.php" class="btn btn-secondary mb-3">← Retour</a>

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
                            <span class="badge bg-danger">Emprunté par : <?= htmlspecialchars($emprunt['mel']) ?></span>
                        <?php endif; ?>
                    </p>

                    <?php if ($disponible && isset($_SESSION['mel'])): ?>
                        <a href="emprunter.php?nolivre=<?= $nolivre ?>" class="btn btn-primary">Emprunter</a>
                    <?php elseif (!$disponible && isset($_SESSION['mel'])): ?>
                        <a href="retour.php?nolivre=<?= $nolivre ?>" class="btn btn-warning">Retourner</a>
                    <?php else: ?>
                        <p class="text-danger">Connectez-vous pour emprunter ce livre.</p>
                    <?php endif; ?>

                    <h2><?= htmlspecialchars($livre['titre']) ?></h2>
                    <p class="text-muted">
                        Auteur : <strong><?= htmlspecialchars($livre['prenomAuteur'].' '.$livre['nomAuteur']) ?></strong><br>
                        Année : <strong><?= $livre['anneeparution'] ?></strong><br>
                        ISBN : <strong><?= $livre['isbn13'] ?></strong><br>
                        Date ajout : <strong><?= $livre['dateajout'] ?></strong>
                    </p>

                    <p><?= nl2br(htmlspecialchars($livre['detail'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
