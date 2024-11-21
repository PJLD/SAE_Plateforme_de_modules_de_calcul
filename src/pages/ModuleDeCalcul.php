<?php
require_once ("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>accueil</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Veuillez saisir vos séries</h2>
<form method='post'>
<label for='SerieA'>Série a</label>
<input type='text' name='SerieA' id='SerieA' placeholder='a,b,c,...'>
<label for='Probabilites'>Probabilités</label>
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

    // Convertir les valeurs en nombres flottants
    foreach ($serie as $key => $value) {
        $serie[$key] = (float)$value;
    }
    foreach ($probabilites as $key => $value) {
        $probabilites[$key] = (float)$value;
    }

    // Calculer le résultat
    $resultat = null;
    if ($calcul == 'Moyenne') {
        $resultat = moyenne($serie);
    } elseif ($calcul == 'Esperance') {
        $resultat = esperance($serie, $probabilites);
    } elseif ($calcul == 'Variance') {
        $resultat = variance($serie, $probabilites);
    } elseif ($calcul == 'Ecart-type') {
        $resultat = ecartType($serie, $probabilites);
    }

    // Afficher le résultat
    echo "<p>Résultat du calcul ($calcul) : <strong>$resultat</strong></p>";
}

include("../templates/footer.html");