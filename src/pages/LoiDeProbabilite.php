<?php
session_start();
include("../templates/header.html");
require_once("../gestion/Fonctions.php");
echo"<title>Loi de Probabilité</title></head>
<body>";
gererNavBar();
echo"<h2>Loi Inverse-Gaussienne</h2>
<form method='post' style='max-width: 25%;'>
<label for='mu'>L'espérance μ</label>
<input type='text' name='mu' id='mu' placeholder='μ'>
<label for='lambda'>La forme λ</label>
<input type='text' name='lambda' id='lambda' placeholder='λ'>
<label for='a'>Valeur de la borne inférieure a (avec a > 0)</label>
<input type='number' name='a' id='a' placeholder='a'>
<label for='t'>t tel que P(X ≤ t) où X suit la loi inverse-gaussienne</label>
<input type='number' name='t' id='t' placeholder='t'>
<label for='n'>Le nombre de sous intervalles</label>
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
    $a=0;
    $mu = $_POST['mu'];
    $lambda = $_POST['lambda'];
    $b = $_POST['t'];
    $n = $_POST['n'];
    $a=$_POST['a'];

    $calcul = $_POST['methode'];
    $message= null;

    if ($calcul =='Methode des trapezes') {
        $result = methodeDesTrapezes($a, $b, $mu, $lambda,$n);
        $moyenne = calculerXBarreTrapeze($a,$b,$mu,$lambda,$n);
    }elseif ($calcul =='Methode des rectangles') {
        $result = methodeDesRectangles($a, $b, $mu, $lambda,$n);
        $moyenne = calculerXBarreRectangles($a,$b,$mu,$lambda,$n);
    }elseif ($calcul =='Methode de Simpson') {
        $result = methodeDeSimpson($a, $b,$mu,$lambda,$n);
        $moyenne=calculerXBarreSimpson($a,$b,$mu,$lambda,$n);
    }
    $sigma = sqrt(pow($mu,3) / $lambda);

    $resultats = [
        'Resultat' => $result,
        'Lambda' => $lambda,
        'Moyenne'=> $moyenne,
        'Ecart-type' => $sigma,
        'Methode' => $calcul
    ];

    echo "<table class='tableau-resultats'>";
    echo "<tbody>";
    foreach ($resultats as $parametre => $valeur) {
        echo "<tr>";
        echo "<td>" . $parametre . "</td>";
        echo '<td>' . $valeur . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";




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