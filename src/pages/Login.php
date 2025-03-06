<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");


echo"
<title>Log In</title>
</head>
<body>";


gererNavBar();


echo"
<h2>Se connecter</h2>
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

    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");

    $sql = "SELECT MDP, Cle FROM Comptes WHERE Login = ?";
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $mdp_chiffre = $row['MDP'];
        $cle_rc4 = $row['Cle'];

        $mdp_dechiffre = rc4_dechiffrer($cle_rc4, $mdp_chiffre);

        if ($mdp_dechiffre === $mdp) {
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['mdp'] = $mdp;
            header("Location: Accueil.php");
            log_connexion($login, true);
            exit();
        }
    }

    echo "<p style='color: red; text-align: center;'>Login ou mot de passe incorrect</p>";
    log_connexion($login, false);
    mysqli_stmt_close($stmt);
    mysqli_close($cnx);
}

include("../templates/footer.html");