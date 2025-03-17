<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");

echo "
<title>Module de Calcul</title>
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
    .deux-div {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        width: 80%;
        margin: 50px auto;
        padding: 20px;
    }
    .formChiffrer, .formDechiffrer {
        background: #f8f8f8;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body>";

gererNavBar();

echo "
<div class='deux-div'>
    <form class='formChiffer' method='post'>
        <h2>Chiffrer avec RC4</h2>
        <label for='MotAChiffre'>Mot à chiffrer</label>
        <input type='text' name='MotAChiffre' id='MotAChiffre' placeholder='Mot' required>
        <button type='submit' name='Chiffrer'>Chiffrer</button>
    </form>
    <form class='formDechiffer' method='post'>
        <h2>Déchiffrer avec RC4</h2>
        <label for='MotADechiffre'>Mot à déchiffrer</label>
        <input type='text' name='MotADechiffre' id='MotADechiffre' placeholder='Mot' required>
        <label for='CleAleatoire'>Clé de déchiffrage</label>
        <input type='text' name='CleAleatoire' id='CleAleatoire' placeholder='Clé' required>
        <button type='submit' name='Dechiffrer'>Déchiffrer</button>
    </form>
</div>";

if (isset($_POST['Chiffrer'])) {
    $login = $_SESSION['login'] ?? null;
    if (!$login) {
        echo "<p style='color: red; text-align: center;'>Vous devez être connecté pour enregistrer votre calcul.</p>";
        exit();
    }

    if (empty($_POST['MotAChiffre'])) {
        echo "<p style='color: red; text-align: center;'>Veuillez saisir un mot à chiffrer.</p>";
        exit();
    }

    $mot = $_POST['MotAChiffre'];
    $cle = bin2hex(random_bytes(16));
    $chiffre = rc4_chiffrer($cle, $mot);
    $calcul = "Cryptage";
    date_default_timezone_set("Europe/Paris");
    $date = (new DateTime())->format("d/m/Y H:i:s");
    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");

    echo "<div class='resultatConteneur'>";
    echo "<h3>Résultat du chiffrement :</h3>";
    echo "<p><strong>Texte original :</strong> $mot</p>";
    echo "<p><strong>Clé utilisée :</strong> $cle</p>";
    echo "<p><strong>Texte chiffré :</strong> $chiffre</p>";

    echo "<a href='?historique=true&login=".$login."&date=".$date."&calcul=RC4(".urlencode($mot).")&result=".$chiffre."' class='link'>Ajouter à l'historique</a>";
    echo "</div>";
    mysqli_close($cnx);
}

if (isset($_POST['Dechiffrer'])) {
    $login = $_SESSION['login'] ?? null;
    if (!$login) {
        echo "<p style='color: red; text-align: center;'>Vous devez être connecté pour enregistrer votre calcul.</p>";
        exit();
    }

    if (empty($_POST['MotADechiffre'])) {
        echo "<p style='color: red; text-align: center;'>Veuillez saisir un mot à déchiffrer.</p>";
        exit();
    }

    $mot = $_POST['MotADechiffre'];
    $cle = $_POST['CleAleatoire'];
    $dechiffre = rc4_dechiffrer($cle, $mot);
    $calcul = "Décryptage";
    date_default_timezone_set("Europe/Paris");
    $date = (new DateTime())->format("d/m/Y H:i:s");
    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");

    echo "<div class='resultatConteneur'>";
    echo "<h3>Résultat du déchiffrage :</h3>";
    echo "<p><strong>Texte chiffré :</strong> $mot</p>";
    echo "<p><strong>Clé utilisée :</strong> $cle</p>";
    echo "<p><strong>Texte déchiffré :</strong> $dechiffre</p>";

    echo "<a href='?historique=true&login=".$login."&date=".$date."&calcul=RC4(".urlencode($mot).")&result=".$dechiffre."' class='link'>Ajouter à l'historique</a>";
    echo "</div>";
    mysqli_close($cnx);
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