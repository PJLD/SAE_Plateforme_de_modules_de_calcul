<?php
session_start();

$cnx = mysqli_connect("localhost", "sae", "sae", "SAE");
if (!$cnx) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Gestion du filtre par date
$dateFilter = isset($_POST['date_filter']) ? $_POST['date_filter'] : '';
$dateFilterFormatted = date('d/m/Y', strtotime($dateFilter));
$whereClause = "WHERE Date LIKE '$dateFilterFormatted%'";

// Requête SQL pour afficher les logs
$sql = "SELECT Date, Login, Statut FROM Logs $whereClause ORDER BY STR_TO_DATE(Date, '%d/%m/%Y %H:%i:%s') DESC;";
$resultat = mysqli_query($cnx, $sql);

// Téléchargement JSON
if (isset($_POST['download_json'])) {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="logs.json"');
    $logs = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $logs[] = $row;
    }
    echo json_encode($logs, JSON_PRETTY_PRINT);
    mysqli_close($cnx);
    exit();
}

require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo "
<title>Journal d'activité</title>
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
   form {
       text-align: center;
       margin-bottom: 20px;
   }
</style>
</head>
<body>";

gererNavBar();

if ($dateFilter) {
    $dateFilterFormatted = date('d/m/Y', strtotime($dateFilter));
    $whereClause = "WHERE Date LIKE '$dateFilterFormatted%'";
}

// Suppression des logs d'une journée spécifique
if (isset($_POST['delete_day'])) {

    $dateFilter = $_POST['date_filter'];

    $dateFilterFormatted = date('d/m/Y', strtotime($dateFilter));
    // Construction de la clause WHERE
    $whereClause = "WHERE Date LIKE '$dateFilterFormatted%'";

    // Préparation de la requête SQL
    $sql = "DELETE FROM Logs WHERE DATE(Date) = '$dateFilterFormatted'";
    $stmt = mysqli_prepare($cnx, $sql);

    if ($stmt) {
        // Exécution de la requête
        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green; text-align: center;'>Logs de la journée supprimés avec succès.</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression des logs.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p style='color: red;'>Erreur dans la préparation de la requête SQL.</p>";
    }
}

// Suppression d’un log spécifique
if (isset($_GET['delete_log']) && isset($_GET['delete_login'])) {
    $logDate = $_GET['delete_log'];
    $logLogin = $_GET['delete_login'];

    $deleteSql = "DELETE FROM Logs WHERE Date = ? AND Login = ?";
    $stmt = mysqli_prepare($cnx, $deleteSql);
    mysqli_stmt_bind_param($stmt, "ss", $logDate, $logLogin);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green; text-align: center;'>Log supprimé avec succès.</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression du log.</p>";
    }
    mysqli_stmt_close($stmt);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Suppression de tous les logs
if (isset($_POST['delete_all'])) {
    $deleteAllSql = "DELETE FROM Logs";
    mysqli_query($cnx, $deleteAllSql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

echo "<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 50px;'>Journal d'Activité</h1>";

// Formulaire de filtre par date
echo "
<form method='post'>
   <label for='date_filter'>Afficher une journée :</label>
   <input type='date' name='date_filter' id='date_filter' value='$dateFilter'>
   <button type='submit'>Filtrer</button>
</form>";

// Formulaires de suppression et export JSON
echo "
<form method='post'>
   <button type='submit' name='delete_day'>Supprimer tous les logs de cette journée</button>
   <button type='submit' name='delete_all'>Supprimer tous les logs</button>
   <button type='submit' name='download_json'>Télécharger les logs en JSON</button>
</form>";

// Affichage du tableau des logs
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
        echo "<td>" . $lignes['Date'] . "</td>";
        echo "<td>" . $lignes['Login'] . "</td>";
        echo "<td>" . $lignes['Statut'] . "</td>";
        echo "<td><a href='?delete_log=" . urlencode($lignes['Date']) . "&delete_login=" . urlencode($lignes['Login']) . "' class='delete-link'>Supprimer</a></td>";
        echo "</tr>";
    } while ($lignes = mysqli_fetch_assoc($resultat));
} else {
    echo "<tr><td colspan='4' style='text-align: center;'>Aucun log trouvé.</td></tr>";
}
echo "</table>";

mysqli_close($cnx);
include("../templates/footer.html");