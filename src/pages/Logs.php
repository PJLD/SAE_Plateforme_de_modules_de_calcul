<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Logs</title>
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

$cnx = mysqli_connect("localhost", "sae", "sae");
$bd = mysqli_select_db($cnx, "SAE");
$login = $_SESSION['login'];

$sql = "SELECT * FROM Logs ORDER BY Date DESC";
$resultat = mysqli_query($cnx, $sql);

echo "<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Base des Logs</h1>";

echo "<table>";

$lignes = mysqli_fetch_assoc($resultat);

if ($lignes) {
    echo "<tr>";
    foreach ($lignes as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "</tr>";

    do {
        echo "<tr>";
        foreach ($lignes as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    } while ($lignes = mysqli_fetch_assoc($resultat));
} else {
    echo "<tr><td colspan='100%' style='text-align: center;'>La table des logs est vide.</td></tr>";
}
echo "</table>";

mysqli_close($cnx);

include("../templates/footer.html");