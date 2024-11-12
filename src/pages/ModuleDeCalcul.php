<?php
include("../templates/header.html");
echo"<title>accueil</title></head>
echo<body>";
include("../templates/navbar.html");
echo"<h2>Veuillez saisir vos séries</h2>
<form method='post'>
<label for='SerieA'>Série a</label>
<input type='text' name='SerieA' id='SerieA' placeholder='1,2,3,...'>
<label for='SerieB'>Série b</label>
<input type='text' name='SerieB' id='SerieB' placeholder='1,2,3,...'>
<button type='submit' name='OK' >OK</button>
<label for='calcul'>Sélectionnez votre calcul</label>
<select name='calcul' id='calcul'>
<option value='moyenne'>Moyenne</option>
<option value='écarttype'>Ecart-type</option>
<option value='esperance'>Esperance</option>
<option value='variance'>Variance</option>
</select>
<button type='submit' name='Calculer' >Calculer</button>
</form>";
include("../templates/footer.html");