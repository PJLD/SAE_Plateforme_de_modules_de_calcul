<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo "
<title>Polynôme</title>
<style>
    .link {
        padding: 10px 20px;  
        font-size: 16px;  
        font-weight: bold;  
        color: white;  
        background-color: #1c305f;
        border: none;  
        border-radius: 5px;  
        text-decoration: none;  
        cursor: pointer;  
        transition: background-color 0.3s, transform 0.2s;
        display: block;
        width: fit-content;
        margin: 20px auto;
    }
    .link:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
</style>
</head>
<body>";

gererNavBar();

echo "
<h2>Module Polynôme</h2>
<form method='post'>
    <label for='a'>Coefficient a</label>
    <input type='text' pattern='^(-?(i|\d+(\.\d*)?i?)$)' name='a' id='a' placeholder='a' required>

    <label for='b'>Coefficient b</label>
    <input type='text' pattern='^(-?(i|\d+(\.\d*)?i?)$)' name='b' id='b' placeholder='b' required>
    
    <label for='c'>Coefficient c</label>
    <input type='text' pattern='^(-?(i|\d+(\.\d*)?i?)$)' name='c' id='c' placeholder='c' required>

    <button type='submit' name='Calculer'>Calculer</button>
</form>";

if (isset($_POST['Calculer'])) {
    $login = $_SESSION['login'] ?? null;
    if (!$login) {
        echo "<p style='color: red; text-align: center;'>Vous devez être connecté pour enregistrer votre calcul.</p>";
        exit();
    }

    $a = convertirNombre($_POST['a']);
    $b = convertirNombre($_POST['b']);
    $c = convertirNombre($_POST['c']);

    $resultat = calculerRacines($a, $b, $c);
    $calcul = "Polynôme : ({$a},{$b},{$c})";

    date_default_timezone_set("Europe/Paris");
    $date = (new DateTime())->format("d/m/Y H:i:s");

    echo "<div class='resultatConteneur'>";
    echo "<h3>Résultat :</h3><p><strong>$resultat</strong></p>";
    echo "<a href='?historique=true&login=".$login."&date=".$date."&calcul=".$calcul."&result=".urlencode($resultat)."' class='link'>Ajouter à l'historique</a>";
    echo "</div>";
}

if (isset($_GET['historique'])) {
    $login = $_GET['login'];
    $date = $_GET['date'];
    $calcul = $_GET['calcul'];
    $result = $_GET['result'];

    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");
    $sql = "INSERT INTO Historique (login, DateHistorique, calcul, resultat) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $login, $date, $calcul, $result);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green; text-align: center;'>Le résultat a été enregistré dans votre historique</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de l'enregistrement du résultat</p>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($cnx);
}

include("../templates/footer.html");