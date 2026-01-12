<?php
    // Connexion SQL
    $pdo = new PDO("mysql:host=localhost;dbname=bibliotheque;charset=utf8", "root", "");

    // Récupération des 3 derniers livres
    $stmt = $pdo->query("SELECT * FROM livres ORDER BY date_ajout DESC LIMIT 3");
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>