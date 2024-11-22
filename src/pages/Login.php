<?php
include("../templates/header.html");
echo"<title>accueil</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Veuillez vous connecter</h2>
<form method='post'>
<label for='Login'>Login</label>
<input type='text' name='Login' id='Login' placeholder='Login'>
<label for='Mdp'>Mot de Passe</label>
<input type='password' name='Mot de Passe' id='Mdp' placeholder='Mot de passe'>
<button type='submit' name='Connexion' >Connexion</button>
</form>
<p><a href='SignIn.php'>Créer un compte</a></p>";

if(isset($_POST['ok'])){
    $login = htmlspecialchars($_POST['Login']);
    $mdp = htmlspecialchars($_POST['Mdp']);
    $mdp2 = md5($mdp);
    $sql = "SELECT * FROM Comptes WHERE Login=? and MDP=?";
    $cnx = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($cnx, "SAES3");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp2);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($res) == 1){
        echo "<p style='font-size: 50px;'>Login ok</p>";
    } else {
        echo "<p style='font-size: 50px;'>Login ou mot de passe incorrect</p>";
    }
}

include("../templates/footer.html");