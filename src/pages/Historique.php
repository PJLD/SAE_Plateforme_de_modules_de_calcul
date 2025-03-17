<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo "
<title>Historique</title>
<style>
    table {
        width: 70%;
        border-collapse: collapse;
        margin: auto;
        font-size: 18px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 15px;
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

$cnx = mysqli_connect("localhost", "sae", "sae", "SAE");
$login = $_SESSION['login'];
$sql = "SELECT ID, Login, DateHistorique, Calcul, Resultat FROM Historique WHERE Login = ? ORDER BY STR_TO_DATE(DateHistorique, '%d/%m/%Y %H:%i:%s') DESC";
$stmt = mysqli_prepare($cnx, $sql);
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);

echo "<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Historique des Calculs</h1>";
echo "
<form method='post' style='all: unset; display: flex; justify-content: center; margin-bottom: 50px;'>
    <button type='submit' name='SupprimerHistorique' style='font-family: cursive; padding: 5px; max-height: 80px; width: 14%; font-size: 14px; background-color: darkred; color: white; border: none; padding: 5px; margin-top: 5px; cursor: pointer;'>Supprimer mon historique</button>
</form>";

// Suppression de tout l'historique
if (isset($_POST['SupprimerHistorique'])) {
    $suppressionHistorique = "DELETE FROM Historique WHERE Login = ?";
    $stmt = mysqli_prepare($cnx, $suppressionHistorique);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Refresh: 0");
    exit();
}

echo "<table>";
$lignes = mysqli_fetch_assoc($resultat);
if ($lignes) {
    echo "<tr>";
    foreach ($lignes as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "<th>Supprimer</th>";
    echo "</tr>";

    do {
        echo "<tr>";
        foreach ($lignes as $value) {
            echo "<td>$value</td>";
        }
        echo "<td><a href='?deleteHistorique=" . $lignes['ID'] . "' class='delete-link'>Supprimer</a></td>";
        echo "</tr>";
    } while ($lignes = mysqli_fetch_assoc($resultat));
} else {
    echo "<tr><td colspan='100%' style='text-align: center;'>Aucun historique trouvé.</td></tr>";
}
echo "</table>";

// Suppression d'une seule ligne de l'historique
if (isset($_GET['deleteHistorique'])) {
    $id = $_GET['deleteHistorique'];
    $suppressionHistorique = "DELETE FROM Historique WHERE ID = ?";
    $stmt = mysqli_prepare($cnx, $suppressionHistorique);
    mysqli_stmt_bind_param($stmt, "s", $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: Historique.php");
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression.</p>";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($cnx);
include("../templates/footer.html");