<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo"
<title>Profil</title>
</head>
<body>";

gererNavBar();

if (isset($_SESSION['login']) && isset($_SESSION['mdp'])) {
    echo "
    <div style='text-align: center; max-width: 600px; margin: auto;'>
        <h2>Bienvenue sur votre profil, <span style='color: #1c305f;'>" . htmlspecialchars($_SESSION['login']) . "</span></h2>
        <h3 style='margin: 10px;'>Modifier mon mot de passe</h3>
        <form method='post' style='text-align: left;'>
            <label for='AncienMDP'>Ancien mot de passe</label>
                <input type='password' name='AncienMDP' id='AncienMDP' placeholder='Votre ancien mot de passe' minlength='6' required><br><br>
            <label for='NouveauMDP'>Nouveau mot de passe :</label>
                <input type='password' name='NouveauMDP' id='NouveauMDP' placeholder='Votre nouveau mot de passe'  minlength='6' required><br><br>
            <button type='submit' name='ModifierMDP' style='width: 75%; background-color: #1c305f; color: white; border: none; padding: 20px; margin-top: 20px; cursor: pointer;'>Modifier mon mot de passe</button>
        </form>
    </div>";

    echo"
    <div style='text-align: center; max-width: 600px; margin: auto;'>
        <h3 style='margin: 10px;'>Supprimer mon compte</h3>
        <form method='post'>
            <label for='Mdp'>Mot de Passe</label>
                <input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe' minlength='6' required>
            <label for='ConfirmerMdp'>Confirmation du Mot de Passe</label>
                <input type='password' name='ConfirmerMdp' id='ConfirmerMdp' placeholder='Confirmation du Mot de Passe' minlength='6' required>
            <button type='submit' name='SupprimerCompte' style='width: 75%; background-color: darkred; color: white; border: none; padding: 20px; margin-top: 20px; cursor: pointer;'>Supprimer mon compte</button>
        </form>
    </div>";

    if (isset($_POST['ModifierMDP'])) {
        $AncienMDP = htmlspecialchars($_POST['AncienMDP']);
        $NouveauMDP = htmlspecialchars($_POST['NouveauMDP']);
        $AncienMDP2 = md5($AncienMDP); // Hachage de l'ancien mot de passe
        $NouveauMDP2 = md5($NouveauMDP); // Hachage du nouveau mot de passe

        $cnx = mysqli_connect("localhost", "sae", "sae");
        $bd = mysqli_select_db($cnx, "SAE");

        // Vérification de l'ancien mot de passe
        $sql_verif = "SELECT * FROM Comptes WHERE Login = ? AND Mdp = ?";
        $stmt_verif = mysqli_prepare($cnx, $sql_verif);
        mysqli_stmt_bind_param($stmt_verif, "ss", $_SESSION['login'], $AncienMDP2);
        mysqli_stmt_execute($stmt_verif);
        $res = mysqli_stmt_get_result($stmt_verif);

        if (mysqli_num_rows($res) == 1) {
            $sql_update = "UPDATE Comptes SET Mdp = ? WHERE Login = ?";
            $stmt_update = mysqli_prepare($cnx, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "ss", $NouveauMDP2, $_SESSION['login']);
            if (mysqli_stmt_execute($stmt_update)) {
                echo "<p style='color: #1c305f; text-align: center;'>Mot de passe mis à jour avec succès.</p>";
                $_SESSION['mdp'] = $NouveauMDP2; // Mettre à jour la session
            } else {
                echo "<p style='color: red; text-align: center;'>Erreur lors de la mise à jour du mot de passe.</p>";
            }
            mysqli_stmt_close($stmt_update);
        }
        else {
            echo "<p style='color: red; text-align: center;'>Ancien mot de passe incorrect.</p>";
        }
        mysqli_stmt_close($stmt_verif);
        mysqli_close($cnx);
    }
} else {
    echo "<h2>Vous n'êtes pas connecté. Veuillez vous connecter pour accéder à votre profil.</h2>";
    echo "<p><a href='Login.php'>Se connecter</a></p>";
}

//traitement de la suppression du compte
if (isset($_POST['SupprimerCompte'])) {
    $mdp1 = $_POST['Mdp'];
    $mdp2 = $_POST['ConfirmerMdp'];
    $mdp2md5 = md5($mdp2);
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $bd = mysqli_select_db($cnx, "SAE");

    $suppression = "DELETE FROM Comptes WHERE Login = ? AND Mdp = ?";
    $stmt = mysqli_prepare($cnx, $suppression);
    $login = $_SESSION['login'];
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp2md5);

    if($mdp1 == $mdp2){
        if (mysqli_stmt_execute($stmt)) {
            log_suppression($login, true);
            session_destroy();
            header("Location: Accueil.php");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression du compte : " . mysqli_error($cnx) . "</p>";
            log_suppression($login, false);
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Les deux mots de passe ne correspondent pas.</p>";
        log_suppression($login, false);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($cnx);
}

include("../templates/footer.html");