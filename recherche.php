<?php
require 'config.php'; // inclut config PDO
require 'entete.php'; // inclut header html

$terme = $_GET['q'] ?? ''; // recupere terme recherche
$livres = []; // tableau pour stocker les livres trouves

if ($terme) { // si utilisateur a saisi 
    $stmt = $pdo->prepare("
        SELECT l.*, a.nom, a.prenom
        FROM livre l
        JOIN auteur a ON l.noauteur = a.noauteur
        WHERE a.nom LIKE ? OR a.prenom LIKE ?
        ORDER BY a.nom, a.prenom, l.titre
    "); // selectionne livres correspondant au nom ou prenom auteur
    $stmt->execute(['%' . $terme . '%', '%' . $terme . '%']); // execute requete avec terme
    $livres = $stmt->fetchAll(); // recupere resultats
}
?>

<h1>Recherche</h1>


<form method="get" action="recherche.php" class="mb-4">
    <input type="text" name="q" class="form-control" placeholder="Rechercher un livre" value="<?php echo htmlspecialchars($terme); ?>" />
</form>

<?php if ($terme && empty($livres)): ?>
    <p>Aucun livre trouvé pour "<?php echo htmlspecialchars($terme); ?>"</p> 
<?php endif; ?>

<div class="row">
<?php foreach ($livres as $livre): ?> 
    <div class="col-md-4 mb-3">
        <div class="card">
            <?php if (!empty($livre['photo'])): ?> 
                <img src="covers/<?php echo htmlspecialchars($livre['photo']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($livre['titre']); ?></h5> 
                <a href="livre.php?nolivre=<?php echo $livre['nolivre']; ?>" class="btn btn-primary">Voir détails</a> 
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php
require 'footer.php'; 
