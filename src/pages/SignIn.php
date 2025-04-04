<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");


echo"
<title>Sign In</title>
</head>
<body>";


gererNavBar();


$elem1 = rand(1, 10);
$elem2 = rand(1, 10);


$valeur_captcha = $elem1 * $elem2;


setcookie('captcha', $valeur_captcha, time() + 1800, "/");


echo"
<h2>Créer un compte</h2>
<form method='post'>
   <label for='Login'>Login</label>
       <input type='text' name='Login' id='Login' placeholder='Login' minlength='6' maxlength='20' required>
   <label for='Mdp'>Mot de Passe</label>
       <input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe' minlength='6' required>
   <label for='ConfirmerMdp'>Confirmation du Mot de Passe</label>
       <input type='password' name='ConfirmerMdp' id='ConfirmerMdp' placeholder='Confirmation du Mot de Passe' minlength='6' required>
   <label for='captcha'>$elem1 * $elem2</label>
       <input type='text' name='captcha' id='captcha' placeholder='Résultat de l opération'>
   <button type='submit' name='Inscription'>S'inscrire</button>
</form>
<p><a href='Login.php'>J'ai déja un compte</a></p>";


//traitement de l'inscription
if (isset($_POST["Inscription"])) {
    $Login = $_POST["Login"];
    $Mdp = $_POST["Mdp"];
    $cle_unique = bin2hex(random_bytes(16));
    $mdp2 = rc4_chiffrer($cle_unique, $Mdp);
    $confirmerMdp = $_POST["ConfirmerMdp"];
    $captcha = htmlspecialchars($_POST['captcha']);


    $cnx = mysqli_connect("localhost", "sae", "sae");
    $bd = mysqli_select_db($cnx, "SAE");


    if (!isset($_COOKIE['captcha']) || $captcha != $_COOKIE['captcha']) {
        echo "<p style='color: red; text-align: center;'>Captcha Incorrect. Veuillez réessayer.</p>";
        $ip = $_SERVER['REMOTE_ADDR'];
        log_inscription($ip, $Login, false);
        mysqli_close($cnx);
    } else {
        $sql = "INSERT INTO  Comptes (Login, MDP, Cle) VALUES (?, ?, ?)";
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
            $ip = $_SERVER['REMOTE_ADDR'];
            log_inscription($ip, $Login, false);
            mysqli_close($cnx);
        } else {
            if ($Mdp == $confirmerMdp) {
                mysqli_stmt_bind_param($stmt, "sss", $Login, $mdp2, $cle_unique);
                if (mysqli_stmt_execute($stmt)) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                    log_inscription($ip, $Login, true);
                    header("Location: Login.php");
                } else {
                    echo "<p style='color: red; text-align: center;'>Erreur lors de l'inscription. Veuillez réessayer</p>";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    log_inscription($ip, $Login, false);
                }
            } else {
                echo "<p style='color: red; text-align: center;'>Les deux mots de passe sont différents.</p>";
                $ip = $_SERVER['REMOTE_ADDR'];
                log_inscription($ip, $Login, false);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($cnx);
        }
    }
}


include("../templates/footer.html");