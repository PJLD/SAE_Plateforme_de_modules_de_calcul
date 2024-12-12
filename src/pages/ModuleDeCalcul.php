<?php
session_start();
require_once ("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Module de Calcul</title></head>
<body>";
gererNavBar();
echo"<h2>Veuillez saisir vos données</h2>
<form method='post'>
<label for='SerieA'>Série (xx,xx,xx,...)</label>
<input type='text' name='SerieA' id='SerieA' placeholder='a,b,c,...'>
<label for='Probabilites'>Probabilités (yy,yy,yy,...)</label>
<input type='text' name='Probabilites' id='Probabilites' placeholder='0.1,0.2,0.3,...'>
<label for='calcul'>Sélectionnez votre calcul</label>
<select name='calcul' id='calcul'>
<option value='Moyenne'>Moyenne</option>
<option value='Esperance'>Esperance</option>
<option value='Variance'>Variance</option>
<option value='Ecart-type'>Ecart-type</option>
</select>
<button type='submit' name='Calculer' >Calculer</button>
</form>";

// Traitement du formulaire
if (isset($_POST['Calculer'])) {
    $serie = explode(',', $_POST['SerieA']);
    $probabilites = explode(',', $_POST['Probabilites']);
    $calcul = $_POST['calcul'];
    $login = $_SESSION['login'];
    $date = date('Y-m-d H:i:s');

    $cnx = mysqli_connect("localhost", "sae", "sae");
    $bd = mysqli_select_db($cnx, "SAE");

    // Calculer le résultat
    $resultat = null;
    $message = null;

    if ($calcul == 'Moyenne') {
        $resultat = moyenne($serie);
    } elseif ($calcul == 'Esperance') {
        if (empty($_POST['Probabilites']) || count($probabilites) == 0) {
            $message = "Veuillez remplir le champ des probabilités.";
        } else {
            $resultat = esperance($serie, $probabilites);
        }
    } elseif ($calcul == 'Variance') {
        if (empty($_POST['Probabilites']) || count($probabilites) == 0) {
            $message = "Veuillez remplir le champ des probabilités.";
        } else {
            $resultat = variance($serie, $probabilites);
        }
    } elseif ($calcul == 'Ecart-type') {
        if (empty($_POST['Probabilites']) || count($probabilites) == 0) {
            $message = "Veuillez remplir le champ des probabilités.";
        } else {
            $resultat = ecartType($serie, $probabilites);
        }
    }

    echo "<div class='resultatConteneur'>";
    if ($message) {
        echo "<h3>Résultat du calcul : $calcul</h3>
              <p><strong>$message</strong></p>";
    } else {
        echo "<h3>Résultat du calcul : $calcul</h3>
              <p><strong>$resultat</strong></p>";

        $sql = "INSERT INTO Historique (login, date, calcul, resultat) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($cnx, $sql);
        mysqli_stmt_bind_param($stmt, "sssd", $login, $date, $calcul, $resultat);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green; text-align: center;'>Le résultat a été enregistré dans votre historique</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de l'enregistrement du résultat dans votre historique</p>";
        }
    }
    echo "</div>";
}

include("../templates/footer.html");