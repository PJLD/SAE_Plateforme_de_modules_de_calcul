<?php
include("../templates/header.html");
echo"<title>accueil</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Créer un compte</h2>
<form method='post'>
<label for='Nom'>Nom</label>
<input type='text' name='Nom' id='Nom' placeholder='Nom'>
<label for='Prenom'>Prénom</label>
<input type='text' name='Prenom' id='Prenom' placeholder='Prénom'>
<label for='Mail'>Mail</label>
<input type='email' name='Mail' id='Mail' placeholder='Adresse mail'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de passe</label>
<input type='password' name='Mot de Passe' id='Mdp' placeholder='Mot de passe'>
<label for='ConfirmerMdp'>Confirmer le mot de passe</label>
<input type='password' name='Confirmer le Mot de Passe' id='ConfirmerMdp' placeholder='Mot de passe'>
<button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href='Login.php'>J'ai déja un compte</a></p>";
include("../templates/footer.html");