<?php
include "config.php"; // inclut config PDO

$nolivre = intval($_GET['nolivre'] ?? 0); // recupere id livre depuis l'url
if($nolivre<=0) die("Livre invalide."); // arrete si id invalide

// met a jour la date de retour pour ce livre si non encore retourne
$stmt = $pdo->prepare("
    UPDATE emprunter 
    SET dateretour=CURDATE() 
    WHERE nolivre=:nolivre AND dateretour IS NULL
");
$stmt->execute(['nolivre'=>$nolivre]); // execute requete

// redirige vers la page du livre avec message retour
header("Location: livre.php?nolivre=$nolivre&msg=retour");
exit;
