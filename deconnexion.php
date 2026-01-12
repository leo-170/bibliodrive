<?php
session_start(); // demarre session
session_unset(); // vide toutes les variables de session
session_destroy(); // detruit la session
header("Location: page_accueil.php"); // redirige vers la page accueil
exit; // stoppe le script
