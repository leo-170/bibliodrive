<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['mel'])) {
    header("Location: connexion.php");
    exit;
}

$mel = $_SESSION['mel'];

if (!isset($_GET['nolivre'])) {
    header("Location: page_accueil.php?msg=livre_non_specifie");
    exit;
}

$nolivre = intval($_GET['nolivre']);

// 1️⃣ Vérifie si l'utilisateur a déjà emprunté ce livre
$stmt = $pdo->prepare("SELECT * FROM emprunter WHERE nolivre = :nolivre AND mel = :mel AND dateretour IS NULL");
$stmt->execute(['nolivre' => $nolivre, 'mel' => $mel]);

if ($stmt->rowCount() > 0) {
    header("Location: livre.php?nolivre=$nolivre&msg=deja_emprunte");
    exit();
}

// 2️⃣ Vérifie si le livre est emprunté par quelqu'un d'autre
$stmt = $pdo->prepare("SELECT * FROM emprunter WHERE nolivre = :nolivre AND dateretour IS NULL");
$stmt->execute(['nolivre' => $nolivre]);

if ($stmt->rowCount() > 0) {
    header("Location: livre.php?nolivre=$nolivre&msg=indisponible");
    exit();
}

// 3️⃣ Insère l'emprunt
$stmt = $pdo->prepare("INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, CURDATE())");
$stmt->execute(['mel' => $mel, 'nolivre' => $nolivre]);

header("Location: livre.php?nolivre=$nolivre&msg=emprunte");
exit();
?>
