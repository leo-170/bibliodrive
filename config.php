<?php

if (!isset($_SESSION)) session_start(); // demarre session si pas deja demarree

// connexion PDO a la base de donnees
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", ""); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // affiche les erreurs PDO comme exceptions
?>
