<?php
include("../templates/header.html");
echo"<title>Loi de Probabilité</title></head>
<body>";
include("../templates/navbar.html");

echo"<h2>Loi Inverse-Gaussienne</h2>
<form method='post' style='max-width: 25%;'>
<label for='Esperance'>L'espérance μ</label>
<input type='text' name='Esperance' id='Esperance' placeholder='μ'>
<label for='Forme'>La forme λ</label>
<input type='text' name='Forme' id='Forme' placeholder='λ'>
<label for='T'>La valeur t <=> P(X≤t) où X suit la loi inverse-gaussienne</label>
<input type='text' name='T' id='T' placeholder='t'>
<label for='NbValeurs'>Le nombre de valeur prise sur l’intervalle n≥1</label>
<input type='text' name='NbValeurs' id='NbValeurs' placeholder='Nombre de valeurs'>
<label for='méthode'>Sélectionnez votre méthode de calcul</label>
<select name='méthode' id='méthode'>
<option value='Méthode1'>Méthode 1</option>
<option value='Méthode2'>Méthode 2</option>
<option value='Méthode3'>Méthode 3</option>
</select>
<button type='submit' name='Calculer' >Calculer</button>
</form>";

include("../templates/footer.html");