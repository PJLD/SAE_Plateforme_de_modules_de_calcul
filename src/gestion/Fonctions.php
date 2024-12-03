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
// fonction pour calculer densité de probabiblité de la loi inverse gaussienne
function loiInverseGaussienne($mu, $lambda) {
    if ($mu <= 0 || $lambda <= 0) {
        throw new InvalidArgumentException("Les paramètres µ et lambda doivent être positifs.");
    }

    $a = mt_rand() / mt_getrandmax();
    $b = mt_rand() / mt_getrandmax();

    $y = sqrt(-2 * log($a));

    $x1 = $mu + $mu * $mu / (2 * $lambda) * ($y + sqrt($y * $y + 4 * $lambda * $mu));

    return $x1;
}

// Fonction pour calculer l'espérance (moyenne) de la loi inverse-gaussienne
function esperanceInverseGaussienne($mu) {
    return $mu;
}

// Fonction pour calculer la variance de la loi inverse-gaussienne
function varianceInverseGaussienne($mu, $lambda) {
    return pow($mu, 3) / $lambda;
}

// Fonction pour calculer l'écart-type de la loi inverse-gaussienne
function ecartTypeInverseGaussienne($mu, $lambda) {
    return sqrt(varianceInverseGaussienne($mu, $lambda));
}
// Fonction pour calculer la fonction de répartition (CDF) de la loi normale standard

function normalCDF($z) {
    return 0.5 * (1 + erf($z / sqrt(2)));
}

// Fonction pour calculer la fonction d'erreur (Erf) pour la CDF de la normale
function erf($x) {
    $t = 1.0 / (1.0 + 0.3275911 * $x);
    $tau = $t * exp(-$x * $x - 1.26551223 + 1.00002368 * $t + 0.37409196 * $t * $t + 0.09678418 * $t * $t * $t - 0.18628806 * $t * $t * $t * $t);
    return $tau;
}

// Fonction pour calculer la fonction de répartition de la loi inverse-gaussienne
function fonctionDeRepartitionInverseGaussienne($x, $mu, $lambda) {
    $sigma = ecartTypeInverseGaussienne($mu, $lambda);

    $term1 = normalCDF(($x - $mu) / $sigma);
    $term2 = exp((2 * $mu / $lambda) - ($x / $mu)) * normalCDF(-($x - $mu) / $sigma);

    return $term1 - $term2;
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