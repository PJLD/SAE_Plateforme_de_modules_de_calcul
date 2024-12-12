<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Log In</title></head>
<body>";
gererNavBar();
echo"<h2>Se connecter</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login' required>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe' required>
<button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href='MotDePasseOublie.php'>Mot de passe oublié ?</a></p>
<p><a href='SignIn.php'>Créer un compte</a></p>";

if(isset($_POST['Connexion'])){
    $login = htmlspecialchars($_POST['Login']);
    $mdp = htmlspecialchars($_POST['Mdp']);
    $mdp2 = md5($mdp);
    $cnx = mysqli_connect("localhost","sae","sae");
    $sql = "SELECT * FROM Comptes WHERE Login=? and MDP=?";
    $bd = mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp2);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($res) == 1){
        session_start(); // Démarrer la session
        $_SESSION['login'] = $login; // Enregistrer le login
        $_SESSION['mdp'] = $mdp;     // Enregistrer le mot de passe
        header("Location: Profil.php"); // Redirection vers la page de profil
        log_connexion($login, true);
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Login ou mot de passe incorrect</p>";
        log_connexion($login, false);
    }
}

include("../templates/footer.html");