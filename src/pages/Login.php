<?php
include("../templates/header.html");
echo"<title>accueil</title></head>";
echo"<body>";
include("../templates/navbar.html");
echo"<h2>Veuillez vous connecter</h2>";
echo"<form method='post'>";
echo"<label for='Login'>Login</label>";
echo"<input type='text' name='Login' id='Login' placeholder='Login'>";
echo"<label for='Mdp'>Mot de Passe</label>";
echo"<input type='password' name='Mot de Passe' id='Mdp' placeholder='Mot de passe'>";
echo"<button type='submit' name='Connexion' >Connexion</button>";
echo"</form>";
echo"<p><a href='SignIn.php'>Créer un compte</a></p>";
include("../templates/footer.html");