<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

$nolivre = intval($_GET['nolivre']);
$mel = "test@exemple.com"; 

if ($nolivre > 0) {
    // Vérifie si déjà emprunté
    $stmt = $pdo->prepare("SELECT * FROM emprunter WHERE nolivre = :nolivre AND dateretour IS NULL");
    $stmt->execute(['nolivre' => $nolivre]);

    if ($stmt->rowCount() == 0) {
        // Faire l'emprunt
        $stmt = $pdo->prepare("INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, CURDATE())");
        $stmt->execute([
            'mel' => $mel,
            'nolivre' => $nolivre
        ]);

        header("Location: livre.php?nolivre=$nolivre&msg=emprunte");
        exit;
    }
}

echo "Livre déjà emprunté.";
