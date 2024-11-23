<?php
include("../templates/header.html");
echo"<title>Calcul CSV</title></head>
<body>";
include("../templates/navbar.html");
echo"<h2>Calculer à partir d'un fichier CSV</h2>
<form method='post'>
<label for='csvFile'>Importez votre fichier CSV :</label>
<input type='file' id='csvFile' name='csvFile' accept='.csv''>
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