<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        <li><a href="ModuleDeCalcul.html">Module de Calcul</a></li>
        <li><a href="CalculCSV.php">Calculer via CSV</a></li>
        <li><a href="Contacts.php">Contacts</a></li>
    </ol>
</nav>
<h2>Veuillez vous connecter</h2>
<form method='post'>
    <label for="Login">Login</label>
    <input type='text' name='Login' id="Login" placeholder='Login'>
    <label for="Mdp">Mot de Passe</label>
    <input type='password' name='Mot de Passe' id="Mdp" placeholder='Mot de passe'>
    <button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href="SignIn.html">Créer un compte</a></p>
<footer>
    <h1 style="padding-top: 30px;">Membres de l'équipe</h1>
    <p>INF2-FI-A</p>
    <div style="padding-bottom: 30px;">
        <p>Bilong Noa - Colombani Esteban - Da Silva Luca - Juillard Pierre - Tramier Joseph</p>
    </div>
</footer>
</body>
</html>