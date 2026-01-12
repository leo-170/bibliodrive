<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE mel = ?");
$stmt->execute([$_POST['mel']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $_POST['motdepasse'] === $user['motdepasse']) {
    $_SESSION['mel'] = $user['mel'];
    $_SESSION['profil'] = $user['profil'];
    header("Location: index.php");
} else {
    echo "Identifiants incorrects";
}