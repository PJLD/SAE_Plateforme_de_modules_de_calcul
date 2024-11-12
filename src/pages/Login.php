<?php
include("../templates/header.html");
echo"<title>accueil</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Veuillez vous connecter</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mot de Passe' id='Mdp' placeholder='Mot de passe'>
<button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href='SignIn.php'>Créer un compte</a></p>";
include("../templates/footer.html");