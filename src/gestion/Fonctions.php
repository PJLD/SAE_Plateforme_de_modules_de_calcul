<?php
//Fonctions nécessaires

//fonction csv en tableau
function tableau($file){
    if (file_exists($file)) {
        $fp = fopen($file, "r");
        echo "<table class='tableau'>";
        $res = fgetcsv($fp, 1024, ";");
        echo "<tr>";
        foreach ($res as $valeur) {
            echo "<th>".$valeur."</th>";
        }
        echo "</tr>";
        while ($res = fgetcsv($fp, 1024, ";")) {
            echo "<tr>";
            foreach ($res as $valeur) {
                echo "<td>".$valeur."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
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




function loiInverseGaussienne($x, $lambda, $mu)
{
    return sqrt($lambda / (2 * M_PI * pow($x, 3))) * exp(-$lambda * pow($x - $mu, 2) / (2 * pow($mu, 2) * $x));
}


function methodeDesTrapezes($loiInverseGaussienne, $a, $b, $lambda, $mu, $n)
{
    $h = ($b - $a) / $n;
    $sum = 0.5 * ($loiInverseGaussienne($a, $lambda, $mu) + $loiInverseGaussienne($b, $lambda, $mu));

    for ($k = 1; $k < $n; $k++) {
        $ak = $a + $k * $h;
        $sum += loiInverseGaussienne($ak,$mu,$lambda);
    }
    return $sum * $h;
}
function methodeDesRectangles($a, $b, $mu, $lambda, $n) {
    $h = ($b - $a) / $n;

    $somme = 0;

    for ($k = 0; $k < $n; $k++) {
        $ak = $a + $k * $h;
        $ak_plus_1 = $ak + $h;
        $moyenne = ($ak + $ak_plus_1) / 2;
        $somme += loiInverseGaussienne($moyenne, $mu, $lambda);
    }

    return $somme * $h;
}







// Fonction pour écrire dans le fichier de log les utilisateurs supprimés

function log_suppression($login, $etat) {
    $file = "../logs/logs.csv";


    if (file_exists($file)) {
        $fp = fopen($file, "a");
    } else {
        $fp = fopen($file, "w");
    }

    if (filesize($file) == 0) {
        fputcsv($fp, ["Date", "Utilisateur", "Statut"], ";");
    }

    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "suppression OK" : "suppression KO";
    fputcsv($fp, [$date, $login, $statut], ";");

    fclose($fp);
}

//fonction pour écrire dans le fichier de log les utilisateurs inscrits
function log_inscription($login, $etat) {
    $file = "../logs/logs.csv";


    if (file_exists($file)) {
        $fp = fopen($file, "a");
    } else {
        $fp = fopen($file, "w");
    }

    if (filesize($file) == 0) {
        fputcsv($fp, ["Date", "Utilisateur", "Statut"], ";");
    }

    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "inscription OK" : "inscription KO";
    fputcsv($fp, [$date, $login, $statut], ";");

    fclose($fp);
}

//fonction pour écrire dans le fichier de log les utilisateurs connectés
function log_connexion($login, $etat) {
    $file = "../logs/logs.csv";


    if (file_exists($file)) {
        $fp = fopen($file, "a");
    } else {
        $fp = fopen($file, "w");
    }

    if (filesize($file) == 0) {
        fputcsv($fp, ["Date", "Utilisateur", "Statut"], ";");
    }

    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "connexion OK" : "connexion KO";
    fputcsv($fp, [$date, $login, $statut], ";");

    fclose($fp);
}

//fonction pour écrire dans le fichier de log les utilisateurs déconnectés
function log_deconnexion($login, $etat) {
    $file = "../logs/logs.csv";


    if (file_exists($file)) {
        $fp = fopen($file, "a");
    } else {
        $fp = fopen($file, "w");
    }

    if (filesize($file) == 0) {
        fputcsv($fp, ["Date", "Utilisateur", "Statut"], ";");
    }

    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "déconnexion OK" : "déconnexion KO";
    fputcsv($fp, [$date, $login, $statut], ";");

    fclose($fp);
}