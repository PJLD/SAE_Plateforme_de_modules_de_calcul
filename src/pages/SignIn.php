<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Sign In</title></head>
<body>";
gererNavBar();

$elem1 = rand(1, 10);
$elem2 = rand(1, 10);

$valeur_captcha = $elem1 * $elem2;

setcookie('captcha', $valeur_captcha, time() + 1800, "/");

echo"<h2>Créer un compte</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login' required>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe' required>
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

    $cnx = mysqli_connect("localhost", "sae", "sae");
    $bd = mysqli_select_db($cnx, "SAE");

    if (!isset($_COOKIE['captcha']) || $captcha != $_COOKIE['captcha']) {
        echo "<p style='color: red; text-align: center;'>Captcha Incorrect. Veuillez réessayer.</p>";
        log_inscription($Login, false);
        mysqli_close($cnx);
    } else {

        $sql = "INSERT INTO  Comptes (Login, MDP) VALUES (?, ?)";

        $stmt = mysqli_prepare($cnx, $sql);

        // Vérifier si le login existe déjà
        $sql_verif = "SELECT COUNT(*) FROM Comptes WHERE Login = ?";
        $stmt_verif = mysqli_prepare($cnx, $sql_verif);
        mysqli_stmt_bind_param($stmt_verif, "s", $Login);
        mysqli_stmt_execute($stmt_verif);
        mysqli_stmt_bind_result($stmt_verif, $existe);
        mysqli_stmt_fetch($stmt_verif);
        mysqli_stmt_close($stmt_verif);

        if ($existe > 0) {
            echo"<p style='color: red; text-align: center;'>Utilisateur $Login existe déjà.</p>";
            log_inscription($Login, false);
            mysqli_close($cnx);
        }

        mysqli_stmt_bind_param($stmt, "ss", $Login, $mdp2);

        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green; text-align: center;'>Inscription réussie. Veuillez accéder à la page Login afin de vous connecter</p>";
            log_inscription($Login, true);
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de l'inscription. Veuillez réessayer</p>";
            log_inscription($Login, false);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($cnx);
    }
}

include("../templates/footer.html");