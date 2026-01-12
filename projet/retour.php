<?php
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

$nolivre = intval($_GET['nolivre']);

if ($nolivre > 0) {
    $stmt = $pdo->prepare("
        UPDATE emprunter
        SET dateretour = CURDATE()
        WHERE nolivre = :nolivre AND dateretour IS NULL
    ");
    $stmt->execute(['nolivre' => $nolivre]);

    header("Location: livre.php?nolivre=$nolivre&msg=retour");
    exit;
}

echo "Impossible de retourner ce livre.";
