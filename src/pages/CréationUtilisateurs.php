<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");


echo "
<title>Créer utilisateurs</title>
</head>
<body>";


gererNavBar();

$cnx = mysqli_connect("localhost", "sae", "sae");
$bd = mysqli_select_db($cnx, "SAE");

echo "
<h2>Créer un compte</h2>
<form method='post'>
   <label for='Login'>Login</label>
       <input type='text' name='Login' id='Login' placeholder='Login' minlength='6' maxlength='20' required>
   <label for='Mdp'>Mot de Passe</label>
       <input type='password' name='Mdp' id='Mdp' placeholder='Mot de passe' minlength='6' required>
   <label for='ConfirmerMdp'>Confirmation du Mot de Passe</label>
       <input type='password' name='ConfirmerMdp' id='ConfirmerMdp' placeholder='Confirmation du Mot de Passe' minlength='6' required>
   <button type='submit' name='Créer'>Créer le compte</button>
</form>";



//traitement de l'inscription
if (isset($_POST["Créer"])) {
    $Login = $_POST["Login"];
    $Mdp = $_POST["Mdp"];
    $cle_unique = bin2hex(random_bytes(16));
    $mdp2 = rc4_chiffrer($cle_unique, $Mdp);
    $confirmerMdp = $_POST["ConfirmerMdp"];
    $captcha = htmlspecialchars($_POST['captcha']);


    $cnx = mysqli_connect("localhost", "sae", "sae");
    $bd = mysqli_select_db($cnx, "SAE");



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
        echo "<p style='color: red; text-align: center;'>Utilisateur $Login existe déjà.</p>";
        $ip = $_SERVER['REMOTE_ADDR'];
        log_inscription($ip, $Login, false);
        mysqli_close($cnx);
    } else {
        if ($Mdp == $confirmerMdp) {
            mysqli_stmt_bind_param($stmt, "sss", $Login, $mdp2, $cle_unique);
            if (mysqli_stmt_execute($stmt)) {
                $ip = $_SERVER['REMOTE_ADDR'];
                log_inscription($ip, $Login, true);
                echo "<p style='color: green; text-align: center;'>Utilisateur créer avec succès</p>";
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

//ajout avec csv
$messages = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {

        // Chemin du fichier temporaire
        $file = $_FILES['csvFile']['tmp_name'];

        // Tableaux pour les logs
        $reussi = [];
        $rate = [];

        // Ouvrir le fichier CSV
        if (($fp = fopen($file, "r")) !== false) {
            $sql = "INSERT INTO Comptes (Login, MDP, Cle) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($cnx, $sql);

            while (($res = fgetcsv($fp, 1024, ",")) != false) {
                if (count($res) >= 2) {
                    $login = $res[0];
                    $mdp = $res[1];
                    $mdp5 = md5($mdp);
                    $cle_unique = bin2hex(random_bytes(16));
                    $mdp_chiffre = rc4_chiffrer($cle_unique, $mdp);

                    // Vérifier si le login existe déjà
                    $sql_verif = "SELECT COUNT(*) FROM Comptes WHERE Login = ?";
                    $stmt_verif = mysqli_prepare($cnx, $sql_verif);
                    mysqli_stmt_bind_param($stmt_verif, "s", $login);
                    mysqli_stmt_execute($stmt_verif);
                    mysqli_stmt_bind_result($stmt_verif, $existe);
                    mysqli_stmt_fetch($stmt_verif);
                    mysqli_stmt_close($stmt_verif);

                    if ($existe > 0) {
                        $messages .= "<p style='color: orange; text-align: center;'>Utilisateur $login existe déjà.</p>";
                        array_push($rate, $login);
                        continue;
                    }

                    // Exécuter l'insertion
                    mysqli_stmt_bind_param($stmt, "sss", $login, $mdp_chiffre, $cle_unique);
                    if (mysqli_stmt_execute($stmt)) {
                        $messages .= "<p style='color: green; text-align: center;'>Utilisateur $login ajouté avec succès.</p>";
                        array_push($reussi, $login);
                    } else {
                        $messages .= "<p style='color: red; text-align: center;'>Impossible d'ajouter l'utilisateur $login.</p>";
                        array_push($rate, $login);
                    }
                } else {
                    $messages .= "<p style='color: orange; text-align: center;'>format incorrect : utilisateur pas ajouté.</p>";
                }
            }
            fclose($fp);
            mysqli_stmt_close($stmt);

            // Enregistrer les logs
            foreach ($reussi as $user) {
                $ip = $_SERVER['REMOTE_ADDR'];
                log_inscription($ip, $user, true);
            }
            foreach ($rate as $user) {
                $ip = $_SERVER['REMOTE_ADDR'];
                log_inscription($ip, $user, false);
            }
        } else {
            $messages .= "<p style='color: red; text-align: center;'>Erreur lors de l'ouverture du fichier CSV.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors du téléchargement du fichier.</p>";
    }
}

echo "
<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Importer un fichier CSV</h1>
<form method='post' enctype='multipart/form-data'>
    <label for='csvFile'>Importez votre fichier CSV :</label>
    <input type='file' id='csvFile' name='csvFile' accept='.csv'>
    <button type='submit'>OK</button>
</form>";

echo "<p>$messages</p>";


include("../templates/footer.html");