<?php
//Fonctions nécessaires

//fonction csv en tableau
function tableau($file){
    if (file_exists($file)) {
        $fp = fopen($file, "r");
        echo "<table class='tableau'>";
        $res = fgetcsv($fp, 1024, ",");
        echo "<tr>";
        foreach ($res as $valeur) {
            echo "<th>".$valeur."</th>";
        }
        echo "</tr>";
        while ($res = fgetcsv($fp, 1024, ",")) {
            echo "<tr>";
            foreach ($res as $valeur) {
                echo "<td>".$valeur."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        header("Location: CalculCSV.php?error");
    }
}

//fonction pour calculer la moyenne
function moyenne($serie){
    if (count($serie) == 0 ) return 0;
    return array_sum($serie)/count($serie);
}

//fonction pour calculer l'esperance
function esperance($serie, $probabilites){
    if (count($serie) != count($probabilites)) return 0;
    $esperance = 0;
    foreach($serie as $index => $valeur){
        $esperance += $valeur*$probabilites[$index];
    }
    return $esperance;
}

//fonction pour calculer la variance
function variance($serie, $probabilites){
    $esperance = esperance($serie, $probabilites);
    $variance = 0;
    foreach($serie as $index => $valeur){
        $variance += $probabilites[$index]*pow($valeur - $esperance, 2);
    }
    return $variance;
}

//fonction pour calculer l'ecart-type
function ecartType($serie, $probabilites){
    $variance = variance($serie, $probabilites);
    return sqrt($variance);
}
// fonction pour calculer la densité de probabiblité de la loi inverse gaussienne
function loiInverseGaussienne($mu, $lambda) {
    if ($mu <= 0 || $lambda <= 0) {
        throw new InvalidArgumentException("Les paramètres mu et lambda doivent être positifs.");
    }

    $p = mt_rand() / mt_getrandmax();
    $q = mt_rand() / mt_getrandmax();

    $y = sqrt(-2 * log($p));

    $x1 = $mu + $mu * $mu / (2 * $lambda) * ($y + sqrt($y * $y + 4 * $lambda * $mu));

    return $x1;
}