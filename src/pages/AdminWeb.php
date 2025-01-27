<?php
session_start();
require_once ("../gestion/Fonctions.php");
include("../templates/header.html");

echo"
<title>AdminWeb</title>
<style>
    /* Styles pour le tableau */
    table {
        width: 50%;
        border-collapse: collapse;
        margin: auto;
        font-size: 18px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 20px;
        text-align: center;
    }


    th {
        background-color: #1c305f;
        color: white;
        font-weight: bold;
    }

    tr:hover {
        background-color: #ddd;
    }
</style>
</head>
<body>";


gererNavBar();

$cnx = mysqli_connect("localhost", "sae", "sae");
$bd = mysqli_select_db($cnx, "SAE");

//Suppression d'utilisateurs
if (isset($_GET['delete'])) {
    $login = $_GET['delete'];
    $suppression = "DELETE FROM Comptes WHERE Login = ?";
    $stmt = mysqli_prepare($cnx, $suppression);
    mysqli_stmt_bind_param($stmt, "s", $login);
    if (mysqli_stmt_execute($stmt)) {
        $suppressionHistorique = "DELETE FROM Historique WHERE Login = ?";
        $stmtHistorique = mysqli_prepare($cnx, $suppressionHistorique);
        mysqli_stmt_bind_param($stmtHistorique, "s", $login);
        if (mysqli_stmt_execute($stmtHistorique)){
            echo "<p style='color: green; text-align: center;'>Utilisateur supprimé avec succès.</p>";
            log_suppression($login, true);
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression.</p>";
        log_suppression($login, false);
    }
    mysqli_stmt_close($stmt);
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
            $sql = "INSERT INTO Comptes (Login, MDP) VALUES (?, ?)";
            $stmt = mysqli_prepare($cnx, $sql);

            while (($res = fgetcsv($fp, 1024, ",")) != false) {
                if (count($res) >= 2) {
                    $login = $res[0];
                    $mdp = $res[1];
                    $mdp5 = md5($mdp);

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
                    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp5);
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
                log_inscription($user, true);
            }
            foreach ($rate as $user) {
                log_inscription($user, false);
            }
        } else {
            $messages .= "<p style='color: red; text-align: center;'>Erreur lors de l'ouverture du fichier CSV.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors du téléchargement du fichier.</p>";
    }
}

//Affichage des utilisateurs
$sql = "SELECT * FROM Comptes";
$result = mysqli_query($cnx, $sql);


echo "<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Liste des utilisateurs</h1>";

echo "<table>";
$lignes = mysqli_fetch_assoc($result);

if ($lignes) {

    echo "<tr>";
    foreach ($lignes as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "<th>Supprimer Compte</th>";
    echo "</tr>";

    do {
        echo "<tr>";
        foreach ($lignes as $key => $value) {
            echo "<td>$value</td>";
        }
        if ($lignes['Login'] != "sysadmin" and $lignes['Login'] != "adminweb") {
            echo "<td><a href='?delete=" . $lignes['Login'] . "' class='delete-link'>Supprimer</a></td>";
        }else{
            echo "<td>Non supprimable</td>";
        }
        echo "</tr>";
    } while ($lignes = mysqli_fetch_assoc($result));
} else {
    echo "<tr><td colspan='100%' style='text-align: center;'>Aucun utilisateur trouvé.</td></tr>";
}
echo "</table>";

echo "
<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Importer un fichier CSV</h1>
<form method='post' enctype='multipart/form-data'>
    <label for='csvFile'>Importez votre fichier CSV :</label>
    <input type='file' id='csvFile' name='csvFile' accept='.csv'>
    <button type='submit'>OK</button>
</form>";

echo "<p>$messages</p>";

mysqli_close($cnx);

include("../templates/footer.html");