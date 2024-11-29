<?php
include("../templates/header.html");
echo"<title>Profil</title></head>
<body>";
include("../templates/navbar.html");

session_start(); // Démarrer la session pour accéder aux données

if (isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
    echo "<h2>Bienvenue sur votre profil, " . htmlspecialchars($_SESSION['login']) . "</h2>";
    echo "<p>Votre login est : <strong>" . htmlspecialchars($_SESSION['login']) . "</strong></p>";
    echo "<p>Votre mot de passe est : <strong>" . htmlspecialchars($_SESSION['mdp']) . "</strong></p>";
} else {
    echo "<h2>Vous n'êtes pas connecté. Veuillez vous connecter pour voir vos informations.</h2>";
    echo "<p><a href='Login.php'>Se connecter</a></p>";
}

include("../templates/footer.html");