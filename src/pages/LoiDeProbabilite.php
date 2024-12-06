<?php
include("../templates/header.html");
require_once("../gestion/Fonctions.php");
echo"<title>Loi de Probabilité</title></head>
<body>";
include("../templates/navbar.html");

echo"<h2>Loi Inverse-Gaussienne</h2>
<form method='post' style='max-width: 25%;'>
<label for='mu'>L'espérance μ</label>
<input type='text' name='mu' id='mu' placeholder='μ'>
<label for='lambda'>La forme λ</label>
<input type='text' name='lambda' id='lambda' placeholder='λ'>
<label for='a'>Valeur de la borne inférieure a (avec a > 0) </label>
<input type='number' name='a' id='a' placeholder='a'>
<label for='b'>Valeur de la borne supérieure b (avec b > 0 et b > a) </label>
<input type='number' name='b' id='b' placeholder='b'>
<label for='n'>Le nombre de sous intervalles, pour la méthode des trapezes</label>
<input type='number' name='n' id='n' placeholder='n'>
<label for='methode'>Sélectionnez votre méthode de calcul</label>
<select name='methode' id='methode'>
<option value='Methode des trapezes'>Méthode des trapezes</option>
<option value='Methode des rectangles'>Méthode des rectangles(médian)</option>
<option value='Methode de Simpson'>Méthode de Simpson</option>
</select>
<button type='submit' name='Calculer' >Calculer</button>
</form>";

if (isset($_POST['Calculer'])) {
    $mu = $_POST['mu'];
    $lambda = $_POST['lambda'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $n = $_POST['n'];

    $calcul = $_POST['methode'];
    $message= null;

    if ($calcul =='Methode des trapezes' ) {
        $result = methodeDesTrapezes($a, $b, $lambda, $mu,$n);
    }elseif ($calcul =='Methode des rectangles' ) {
        $result = methodeDesRectangles($a, $b, $lambda, $mu,$n);
    }elseif ($calcul =='Methode de Simpson' ) {
        $result = methodeDeSimpson($a, $b, $lambda, $mu,$n);
    }




    echo "<div class='resultatConteneur'>";
    if ($message) {
        echo "<h3>Résultat du calcul : $calcul</h3>
              <p><strong>$message</strong></p>";
    } else {
        echo "<h3>Résultat du calcul : $calcul</h3>
              <p><strong>$result</strong></p>";
    }
    echo "</div>";

}

include("../templates/footer.html");