<?php
include("../templates/header.html");
echo"<title>AdminWeb</title>
<style>
    /* Styles pour le tableau */
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }


    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>
</head>
<body>";
include("../templates/navbar.html");
$cnx = mysqli_connect("localhost", "root", "");
$bd = mysqli_select_db($cnx, "SAE");

//Suppression d'utilisateurs

if (isset($_GET['delete'])) {
    $login = $_GET['delete'];
    $suppression = "DELETE FROM Comptes WHERE Login = ?";
    $stmt = mysqli_prepare($cnx, $suppression);
    mysqli_stmt_bind_param($stmt, "i", $login);
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green; text-align: center;'>Utilisateur supprimé avec succès.</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de la suppression.</p>";
    }
    mysqli_stmt_close($stmt);
}


//Affichage des utilisateurs
$sql = "SELECT * FROM Comptes";
$result = mysqli_query($cnx, $sql);


echo "<h3>Liste des utilisateurs</h3>";

echo "<table>";
// Affiche  l'en-têtes du tableau
$colonnes = array_keys(mysqli_fetch_assoc($result));
echo "<tr>";
foreach ($colonnes as $colonne) {
    echo "<th>$colonne</th>";
}
echo "<th>Supprimer</th>";
echo "</tr>";


// Affiche les lignes de données
while($lignes = mysqli_fetch_assoc($result)){
    echo "<tr>";
    foreach($lignes as $key => $value){
        echo "<td>$value</td>";
    }
    echo "<td>
            <a href='?delete=" . $lignes['Login'] . "' class='delete-link'>Supprimer</a>
        </td>";
    echo "</tr>";
};
echo "</table>";


mysqli_close($cnx);





include("../templates/footer.html");