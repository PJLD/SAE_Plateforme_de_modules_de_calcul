<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo "
<title>Profil</title>
</head>
<body>";

gererNavBar();

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];

    echo "
    <div style='text-align: center; max-width: 600px; margin: auto;'>
        <h2>Bienvenue sur votre profil, <span style='color: #1c305f;'>". htmlspecialchars($login) ."</span></h2>
        
        <h3 style='margin: 10px;'>Modifier mon mot de passe</h3>
        <form method='post' style='text-align: left;'>
            <label for='AncienMDP'>Ancien mot de passe</label>
            <input type='password' name='AncienMDP'  placeholder='Ancien mot de passe' minlength='6' required><br><br>

            <label for='NouveauMDP'>Nouveau mot de passe</label>
            <input type='password' name='NouveauMDP' placeholder='Nouveau mot de passe' minlength='6' required><br><br>

            <button type='submit' name='ModifierMDP' style='width: 75%; background-color: #1c305f; color: white; border: none; padding: 20px; margin-top: 20px; cursor: pointer;'>Modifier mon mot de passe</button>
        </form>
        
        <h3 style='margin: 10px;'>Supprimer mon compte</h3>
        <form method='post' style='text-align: left;'>
            <label for='Mdp'>Mot de passe</label>
            <input type='password' name='Mdp' placeholder='Mot de passe' minlength='6' required><br><br>

            <label for='ConfirmerMdp'>Confirmation du Mot de Passe</label>
            <input type='password' name='ConfirmerMdp' placeholder='Confirmation du Mot de Passe' minlength='6' required><br><br>

            <button type='submit' name='SupprimerCompte' style='width: 75%; background-color: darkred; color: white; border: none; padding: 20px; margin-top: 20px; cursor: pointer;'>Supprimer mon compte</button>
        </form>
    </div>";

    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");

    //traitement de la modification du mot de passe
    if (isset($_POST['ModifierMDP'])) {
        $ancienMDP = $_POST['AncienMDP'];
        $nouveauMDP = $_POST['NouveauMDP'];

        $sql = "SELECT MDP, Cle FROM Comptes WHERE Login = ?";
        $stmt = mysqli_prepare($cnx, $sql);
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $mdp_chiffre = $row['MDP'];
            $cle_rc4 = $row['Cle'];

            $mdp_dechiffre = rc4_dechiffrer($cle_rc4, $mdp_chiffre);

            if ($mdp_dechiffre === $ancienMDP) {
                $nouveauMDP_chiffre = rc4_chiffrer($cle_rc4, $nouveauMDP);

                $sql_update = "UPDATE Comptes SET MDP = ? WHERE Login = ?";
                $stmt_update = mysqli_prepare($cnx, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "ss", $nouveauMDP_chiffre, $login);

                if (mysqli_stmt_execute($stmt_update)) {
                    echo "<p style='color: green;'>Mot de passe mis à jour avec succès.</p>";
                    $_SESSION['mdp'] = $nouveauMDP_chiffre;
                } else {
                    echo "<p style='color: red;'>Erreur lors de la mise à jour du mot de passe.</p>";
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "<p style='color: red;'>Ancien mot de passe incorrect.</p>";
            }
        }
        mysqli_stmt_close($stmt);
    }

    //traitement de la suppression du compte
    if (isset($_POST['SupprimerCompte'])) {
        $mdp1 = $_POST['Mdp'];
        $mdp2 = $_POST['ConfirmerMdp'];

        if ($mdp1 !== $mdp2) {
            echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
        } else {
            $sql_suppression = "SELECT MDP, Cle FROM Comptes WHERE Login = ?";
            $stmt_suppression = mysqli_prepare($cnx, $sql_suppression);
            mysqli_stmt_bind_param($stmt_suppression, "s", $login);
            mysqli_stmt_execute($stmt_suppression);
            $result_suppression = mysqli_stmt_get_result($stmt_suppression);

            if ($row = mysqli_fetch_assoc($result_suppression)) {
                $mdp_chiffre_suppression = $row['MDP'];
                $cle_rc4_suppression = $row['Cle'];

                $mdp_dechiffre_suppression = rc4_dechiffrer($cle_rc4_suppression, $mdp_chiffre_suppression);

                if ($mdp_dechiffre_suppression === $mdp1) {
                    $sql_delete = "DELETE FROM Comptes WHERE Login = ?";
                    $stmt_delete = mysqli_prepare($cnx, $sql_delete);
                    mysqli_stmt_bind_param($stmt_delete, "s", $login);

                    if (mysqli_stmt_execute($stmt_delete)) {
                        log_suppression($login, true);
                        session_destroy();
                        header("Location: Accueil.php");
                        exit();
                    } else {
                        echo "<p style='color: red;'>Erreur lors de la suppression du compte.</p>";
                        log_suppression($login, false);
                    }
                    mysqli_stmt_close($stmt_delete);
                } else {
                    echo "<p style='color: red;'>Mot de passe incorrect.</p>";
                }
            }
            mysqli_stmt_close($stmt_suppression);
        }
    }

    mysqli_close($cnx);
} else {
    echo "<h2>Vous n'êtes pas connecté.</h2>";
    echo "<p><a href='Login.php'>Se connecter</a></p>";
}

include("../templates/footer.html");