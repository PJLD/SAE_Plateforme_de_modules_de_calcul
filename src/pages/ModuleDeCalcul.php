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
</style>
</head>
<body>";

gererNavBar();

echo "
<h2>Veuillez saisir vos données</h2>
<form method='post'>
    <label for='SerieA'>Série (xx,xx,xx,...)</label>
    <input type='text' name='SerieA' id='SerieA' placeholder='a,b,c,...' required>

    <label for='Probabilites'>Probabilités (yy,yy,yy,...)</label>
    <input type='text' name='Probabilites' id='Probabilites' placeholder='0.1,0.2,0.3,...' required>

    <label for='calcul'>Sélectionnez votre calcul</label>
    <select name='calcul' id='calcul' required>
        <option value='Moyenne'>Moyenne</option>
        <option value='Esperance'>Espérance</option>
        <option value='Variance'>Variance</option>
        <option value='Ecart-type'>Écart-type</option>
    </select>
    <button type='submit' name='Calculer'>Calculer</button>
</form>";

if (isset($_POST['Calculer'])) {
    $login = $_SESSION['login'] ?? null;
    if (!$login) {
        echo "<p style='color: red; text-align: center;'>Vous devez être connecté pour enregistrer votre calcul.</p>";
        exit();
    }

    if (empty($_POST['SerieA']) || empty($_POST['Probabilites'])) {
        echo "<p style='color: red; text-align: center;'>Veuillez remplir tous les champs.</p>";
        exit();
    }

    $serie = array_map('floatval', explode(',', $_POST['SerieA']));
    $probabilites = array_map('floatval', explode(',', $_POST['Probabilites']));
    $calcul = $_POST['calcul'];
    date_default_timezone_set("Europe/Paris");
    $date = (new DateTime())->format("d/m/Y H:i:s");
    $cnx = mysqli_connect("localhost", "sae", "sae", "SAE");
    $resultat = null;
    $message = null;

    switch ($calcul) {
        case 'Moyenne':
            $resultat = moyenne($serie);
            break;
        case 'Esperance':
            $resultat = esperance($serie, $probabilites);
            break;
        case 'Variance':
            $resultat = variance($serie, $probabilites);
            break;
        case 'Ecart-type':
            $resultat = ecartType($serie, $probabilites);
            break;
        default:
            $message = "Calcul non valide.";
    }

    echo "<div class='resultatConteneur'>";
    if ($message) {
        echo "<h3>Résultat du calcul : $calcul</h3><p><strong>$message</strong></p>";
    } else {
        echo "<h3>Résultat du calcul : $calcul</h3><p><strong>$resultat</strong></p>";
        echo "<a href='?historique=true&login=".$login."&date=".$date."&calcul=".$calcul."&result=".$resultat."' class='link'>Ajouter à l'historique</a>";
    }
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