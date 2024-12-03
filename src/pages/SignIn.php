<?php
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Sign In</title></head>
<body>";
include("../templates/navbar.html");

$elem1 = rand(1, 10);
$elem2 = rand(1, 10);

$valeur_captcha = $elem1 * $elem2;

setcookie('captcha', $valeur_captcha, time() + 1800, "/");

echo"<h2>Créer un compte</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe'>
<label for='captcha'>$elem1 * $elem2</label>
<input type='text' name='captcha' id='captcha' placeholder='Résultat de l opération'>
<button type='submit' name='Inscription'>S'inscrire</button>
</form>
<p><a href='Login.php'>J'ai déja un compte</a></p>";


if (isset($_POST["Inscription"])) {
    $Login = $_POST["Login"];
    $Mdp = $_POST["Mdp"];
    $mdp2 = md5($Mdp);
    $captcha = htmlspecialchars($_POST['captcha']);

    if (!isset($_COOKIE['captcha']) || $captcha != $_COOKIE['captcha']) {
        echo "<p style='font-size: 50px;'>Captcha incorrect</p>";
        exit();
    }

    $cnx = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($cnx, "SAE");

    $sql = "INSERT INTO  Comptes (Login, Mdp) VALUES (?, ?)";

    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $Login, $mdp2);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='font-size: 50px;'>Inscription réussie !</p>";
        log_inscription($Login, true);
        } else {
            echo "<p style='font-size: 50px;'>Captcha incorrect</p>";
            log_inscription($Login, false);
        }

    mysqli_stmt_close($stmt);
    mysqli_close($cnx);
}

include("../templates/footer.html");