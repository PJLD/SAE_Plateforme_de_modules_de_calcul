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
            $ip = $_SERVER['REMOTE_ADDR'];
            log_suppression($ip, $login, true);
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression.</p>";
        $ip = $_SERVER['REMOTE_ADDR'];
        log_suppression($ip, $login, false);
    }
    mysqli_stmt_close($stmt);
}



//Affichage des utilisateurs
$sql = "SELECT Login, MDP FROM Comptes";
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



mysqli_close($cnx);

include("../templates/footer.html");