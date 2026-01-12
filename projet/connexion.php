<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mel = trim($_POST['mel']);
    $motdepasse = $_POST['motdepasse'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE mel = :mel");
    $stmt->execute(['mel' => $mel]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // test mot de passe 
    if ($utilisateur && $motdepasse === $utilisateur['motdepasse']) {
        $_SESSION['utilisateur'] = [
            'mel' => $utilisateur['mel'],
            'nom' => $utilisateur['nom'],
            'prenom' => $utilisateur['prenom'],
            'profil' => $utilisateur['profil']
        ];

        // redirection 
        if ($utilisateur['profil'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: connexion.php");
        }
        exit;
    } else {
        $_SESSION['connexion_message'] = "Email ou mot de passe incorrect.";
        header("Location: connexion.php");
        exit;
    }
} else {
    header("Location: page_accueil.php");
    exit;
}
