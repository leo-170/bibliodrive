<?php
include "config.php";

$nolivre = intval($_GET['nolivre'] ?? 0);
if($nolivre<=0) die("Livre invalide.");

$stmt = $pdo->prepare("UPDATE emprunter SET dateretour=CURDATE() WHERE nolivre=:nolivre AND dateretour IS NULL");
$stmt->execute(['nolivre'=>$nolivre]);

header("Location: livre.php?nolivre=$nolivre&msg=retour");
exit;
