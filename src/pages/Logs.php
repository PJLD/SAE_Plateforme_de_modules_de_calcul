<?php
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Logs</title>
<style>
    .tableau {
        width: 50%;
        border-collapse: collapse;
        margin: auto;
        font-size: 18px;
    }

    .tableau th, .tableau td {
        border: 1px solid #ddd;
        padding: 20px;
        text-align: center;
    }

    .tableau th {
        background-color: #1c305f;
        color: white;
        font-weight: bold;
    }

    .tableau tr:hover {
        background-color: #ddd;
    }
</style>
</head>
<body>";
include("../templates/navbar.html");
echo"<div style='height: 80px;'></div>";
echo "<h1 style='text-align: center; color: #1c305f; margin-bottom: 80px;'>Tableau des logs</h1>";
if (file_exists("../logs/logs.csv")) {
    if (filesize("../logs/logs.csv") > 0) {
        tableau("../logs/logs.csv");
    } else {
        echo "<h2 style='text-align: center; color: #1c305f; margin-top: 100px; font-size: 22px;'>Le fichier des logs est vide.</h2>";
    }
} else {
    echo "Le fichier n'existe pas.";
}
include("../templates/footer.html");