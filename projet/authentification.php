<?php

if (!isset($_SESSION)) session_start();
?>

<div class="auth">
<?php if (isset($_SESSION['utilisateur'])): ?>
    Bonjour, <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?> <?= htmlspecialchars($_SESSION['utilisateur']['nom']) ?>

    <?php if ($_SESSION['utilisateur']['profil'] === 'admin'): ?>
        <span class="badge bg-warning text-dark ms-2">Admin</span>
    <?php endif; ?>

    <a href="deconnexion.php" class="btn btn-sm btn-secondary ms-2">Déconnexion</a>
<?php else: ?>
    <form method="post" action="connexion.php" class="d-inline">
        <input type="email" name="mel" placeholder="Email" required>
        <input type="password" name="motdepasse" placeholder="Mot de passe" required>
        <button class="btn btn-sm btn-primary">Connexion</button>
    </form>

    <?php if (!empty($_SESSION['connexion_message'])): ?>
        <div class="text-danger mt-1"><?= htmlspecialchars($_SESSION['connexion_message']) ?></div>
        <?php unset($_SESSION['connexion_message']); ?>
    <?php endif; ?>
<?php endif; ?>
</div>

