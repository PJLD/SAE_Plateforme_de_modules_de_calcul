<?php
include("../templates/header.html");
echo"<title>Sign In</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Créer un compte</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe'>
<button type='submit' name='Inscription'>S'inscrire</button>
</form>
<p><a href='Login.php'>J'ai déja un compte</a></p>";


if (isset($_POST["Inscription"])) {
    $Login = $_POST["Login"];
    $Mdp = $_POST["Mdp"];
    $mdp2 = md5($Mdp);

    $cnx = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($cnx, "SAE");

    $sql = "INSERT INTO  Comptes (Login, Mdp) VALUES (?, ?)";

    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $Login, $mdp2);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Inscription réussie !</p>";
    } else {
        echo "<p>Erreur lors de l'inscription.</p>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($cnx);
}

include("../templates/footer.html");
