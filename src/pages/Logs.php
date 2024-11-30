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
echo"<div style='height: 100px;'></div>";
if (file_exists("../logs/suppressions.csv")) {
    tableau("../logs/suppressions.csv");
} else {
    echo "Le fichier n'existe pas.";
}include("../templates/footer.html");