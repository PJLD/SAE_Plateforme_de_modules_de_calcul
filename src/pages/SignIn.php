<?php
include("../templates/header.html");
echo"<title>Sign In</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Créer un compte</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de passe</label>
<input type='password' name='Mot de Passe' id='Mdp' placeholder='Mot de passe'>
<button type='submit' name='Inscription'>S'inscrire</button>
</form>
<p><a href='Login.php'>J'ai déja un compte</a></p>";
include("../templates/footer.html");