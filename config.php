<?php

if (!isset($_SESSION)) session_start();


$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
