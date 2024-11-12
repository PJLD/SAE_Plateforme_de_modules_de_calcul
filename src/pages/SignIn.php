<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/moncss.css">
</head>
<body>
<div class="header">
    <img src="../images/Logo.png" alt="Logo du site" class="logo">
    <h1>CALCUB</h1>
</div>
<nav>
    <ol>
        <li><a href="Accueil.php">Accueil</a></li>
        <li><a href="ModuleDeCalcul.php">Module de Calcul</a></li>
        <li><a href="CalculCSV.php">Calculer via CSV</a></li>
        <li><a href="Contacts.php">Contacts</a></li>
    </ol>
</nav>
<h2>Créer un compte</h2>
<form method='post'>
    <label for="Nom">Nom</label>
    <input type='text' name='Nom' id="Nom" placeholder='Nom'>
    <label for="Prenom">Prénom</label>
    <input type='text' name='Prenom' id="Prenom" placeholder='Prénom'>
    <label for="Mail">Mail</label>
    <input type='email' name='Mail' id="Mail" placeholder='Adresse mail'>
    <label for="Login">Login</label>
    <input type='text' name='Login' id="Login" placeholder='Login'>
    <label for="Mdp">Mot de passe</label>
    <input type='password' name='Mot de Passe' id="Mdp" placeholder='Mot de passe'>
    <label for="ConfirmerMdp">Confirmer le mot de passe</label>
    <input type='password' name='Confirmer le Mot de Passe' id="ConfirmerMdp" placeholder='Mot de passe'>
    <button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href="Login.php">J'ai déja un compte</a></p>
<footer>
    <h1 style="padding-top: 30px;">Membres de l'équipe</h1>
    <p>INF2-FI-A</p>
    <div style="padding-bottom: 30px;">
        <p>Bilong Noa - Colombani Esteban - Da Silva Luca - Juillard Pierre - Tramier Joseph</p>
    </div>
</footer>
</body>
</html>