<?php
//Fonctions nécessaires

//fonction pour gérer la barre de navigation selon le compte
function gererNavBar(){
    /**
     * gestion de la barre de navigation
     */
    if (isset($_SESSION['login']) && $_SESSION['mdp'] === 'adminweb') {
        include("../templates/navbarAdminWeb.html");
    } else if (isset($_SESSION['login']) && $_SESSION['mdp'] === 'sysadmin') {
        include("../templates/navbarSysAdmin.html");
    } else if (empty($_SESSION['login']) && empty($_SESSION['mdp'])) {
        include("../templates/navbarVisiteur.html");
    }
    else {
        include("../templates/navbar.html");
    }
}


//fonction pour calculer la moyenne
function moyenne($serie){
    /**
     * calcul la moyenne
     *
     * @param $serie : tableau de valeur
     *
     * @return float : valeur de la moyenne
     */
    if (count($serie) == 0 ) return 0;
    return array_sum($serie)/count($serie);
}

//fonction pour calculer l'esperance
function esperance($serie, $probabilites){
    /**
     * calcul l'esperance
     *
     * @param $serie : tableau de valeur
     * @param $probabilites : tableau des probabilités des valeurs
     *
     * @return float : valeur de l'esperance
     */
    if (count($serie) != count($probabilites)) return 0;
    $esperance = 0;
    foreach($serie as $index => $valeur){
        $esperance += $valeur*$probabilites[$index];
    }
    return $esperance;
}

//fonction pour calculer la variance
function variance($serie, $probabilites){
    /**
     * calcul la variance
     *
     * @param $serie : tableau de valeur
     * @param $probabilites : tableau des probabilités des valeurs
     *
     * @return float : valeur de la variance
     */
    $esperance = esperance($serie, $probabilites);
    $variance = 0;
    foreach($serie as $index => $valeur){
        $variance += $probabilites[$index]*pow($valeur - $esperance, 2);
    }
    return $variance;
}

//fonction pour calculer l'ecart-type
function ecartType($serie, $probabilites){
    /**
     * calcul l'écart type
     *
     * @param $serie : tableau de valeur
     * @param $probabilites : tableau des probabilités des valeurs
     *
     * @return float : valeur de l'ecart type
     */
    $variance = variance($serie, $probabilites);
    return sqrt($variance);
}



function loiInverseGaussienne($x, $mu, $lambda) {
    /**
     * Calcule la densité de probabilité de la loi inverse gaussienne.
     *
     * @param float $x : La valeur à évaluer (doit être positive).
     * @param float $mu : Paramètre de moyenne de la loi.
     * @param float $lambda : Paramètre d'échelle de la loi.
     *
     * @return float : La densité de probabilité à $x.
     */
    return sqrt($lambda / (2 * M_PI * pow($x, 3))) * exp(-$lambda * pow($x - $mu, 2) / (2 * pow($mu, 2) * $x));
}

function methodeDesTrapezes($a, $b, $mu, $lambda, $n) {
    /**
     * intégration numérique par la méthode des trapèzes.
     *
     * @param float $a : Limite inférieure de l'intégrale.
     * @param float $b : Limite supérieure de l'intégrale.
     * @param float $mu : Paramètre de moyenne de la loi inverse gaussienne.
     * @param float $lambda : Paramètre d'échelle de la loi inverse gaussienne.
     * @param int $n : Nombre de subdivisions.
     *
     * @return float : La valeur de l'intégrale.
     */
    $h = ($b - $a) / $n;
    $sum = 0.5 * (loiInverseGaussienne($a, $mu, $lambda) + loiInverseGaussienne($b, $mu, $lambda));

    for ($k = 1; $k < $n; $k++) {
        $ak = $a + $k * $h;
        $sum += loiInverseGaussienne($ak, $mu, $lambda);
    }
    return $sum * $h;
}

function methodeDesRectangles($a, $b, $mu, $lambda, $n) {
    /**
     * Effectue l'intégration numérique par la méthode des rectangles.
     *
     * @param float $a : Limite inférieure de l'intégrale.
     * @param float $b : Limite supérieure de l'intégrale.
     * @param float $mu : Paramètre de moyenne de la loi inverse gaussienne.
     * @param float $lambda : Paramètre d'échelle de la loi inverse gaussienne.
     * @param int $n : Nombre de rectangles.
     *
     * @return float : La valeur de l'intégrale.
     */
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

function methodeDeSimpson($a, $b, $mu, $lambda, $n) {
    /**
     * Effectue l'intégration numérique par la méthode de Simpson.
     *
     * @param float $a : Limite inférieure de l'intégrale.
     * @param float $b : Limite supérieure de l'intégrale.
     * @param float $mu : Paramètre de moyenne de la loi inverse gaussienne.
     * @param float $lambda : Paramètre d'échelle de la loi inverse gaussienne.
     * @param int $n : Nombre de subdivisions (doit être pair).
     *
     * @return float|null : La valeur de l'intégrale, ou null si $n est impair.
     */
    if ($n % 2 != 0) {
        echo "Le nombre de subdivisions n doit être pair.\n";
        return null;
    }

    $h = ($b - $a) / $n;
    $somme = loiInverseGaussienne($a, $mu, $lambda) + loiInverseGaussienne($b, $mu, $lambda);

    for ($k = 1; $k < $n; $k += 2) {
        $ak = $a + $k * $h;
        $somme += 4 * loiInverseGaussienne($ak, $mu, $lambda);
    }

    for ($k = 2; $k < $n - 1; $k += 2) {
        $ak = $a + $k * $h;
        $somme += 2 * loiInverseGaussienne($ak, $mu, $lambda);
    }

    return ($b - $a) * $somme / (6 * $n);
}

function calculerXBarreTrapeze($a, $b, $mu, $lambda, $n) {
    /**
     * Calcule la moyenne pondérée avec la méthode des trapèzes.
     *
     * @param float $a : Limite inférieure.
     * @param float $b : Limite supérieure.
     * @param float $mu : Paramètre de moyenne de la loi.
     * @param float $lambda : Paramètre d'échelle de la loi.
     * @param int $n : Nombre de subdivisions.
     *
     * @return float : La moyenne pondérée.
     */
    $h = ($b - $a) / $n;
    $sum = 0.5 * ($a * loiInverseGaussienne($a, $mu, $lambda) + $b * loiInverseGaussienne($b, $mu, $lambda));

    for ($k = 1; $k < $n; $k++) {
        $ak = $a + $k * $h;
        $sum += $ak * loiInverseGaussienne($ak, $mu, $lambda);
    }

    return $sum * $h;
}

function calculerXBarreRectangles($a, $b, $mu, $lambda, $n) {
    /**
     * Calcule la moyenne pondérée par la méthode des rectangles.
     *
     * @param float $a : Limite inférieure.
     * @param float $b : Limite supérieure.
     * @param float $mu : Paramètre de moyenne de la loi.
     * @param float $lambda : Paramètre d'échelle de la loi.
     * @param int $n : Nombre de subdivisions.
     *
     * @return float : La moyenne pondérée.
     */
    $h = ($b - $a) / $n;
    $sumNumerateur = 0;
    $sumDenominateur = 0;

    for ($k = 0; $k < $n; $k++) {
        $ak = $a + $k * $h;
        $ak_plus_1 = $ak + $h;
        $moyenne = ($ak + $ak_plus_1) / 2;

        $sumNumerateur += $moyenne * loiInverseGaussienne($moyenne, $mu, $lambda);
        $sumDenominateur += loiInverseGaussienne($moyenne, $mu, $lambda);
    }

    return $sumNumerateur / $sumDenominateur;
}

function calculerXBarreSimpson($a, $b, $mu, $lambda, $n) {
    /**
     * Calcule la moyenne pondérée par la méthode de Simpson.
     *
     * @param float $a : Limite inférieure.
     * @param float $b : Limite supérieure.
     * @param float $mu : Paramètre de moyenne de la loi.
     * @param float $lambda : Paramètre d'échelle de la loi.
     * @param int $n : Nombre de subdivisions (doit être pair).
     *
     * @return float|null : La moyenne pondérée, ou null si $n est impair.
     */
    if ($n % 2 != 0) {
        echo "Le nombre de subdivisions n doit être pair.\n";
        return null;
    }

    $h = ($b - $a) / $n;
    $sumNumerateur = loiInverseGaussienne($a, $mu, $lambda) * $a + loiInverseGaussienne($b, $mu, $lambda) * $b;
    $sumDenominateur = loiInverseGaussienne($a, $mu, $lambda) + loiInverseGaussienne($b, $mu, $lambda);

    for ($k = 1; $k < $n; $k += 2) {
        $ak = $a + $k * $h;
        $sumNumerateur += 4 * $ak * loiInverseGaussienne($ak, $mu, $lambda);
        $sumDenominateur += 4 * loiInverseGaussienne($ak, $mu, $lambda);
    }

    for ($k = 2; $k < $n - 1; $k += 2) {
        $ak = $a + $k * $h;
        $sumNumerateur += 2 * $ak * loiInverseGaussienne($ak, $mu, $lambda);
        $sumDenominateur += 2 * loiInverseGaussienne($ak, $mu, $lambda);
    }

    return $sumNumerateur / $sumDenominateur;
}

function log_suppression($login, $etat) {
    /**
     * Enregistre dans les logs les suppressions d'utilisateurs.
     *
     * @param string $login : Nom d'utilisateur.
     * @param bool $etat : statu de la suppression.
     *
     * @return void
     */
    date_default_timezone_set("Europe/Paris");
    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "suppression OK" : "suppression KO";
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $sql = "INSERT INTO Logs (Date, Login, Statut) VALUES (?, ?, ?)";
    mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $date, $login, $statut);
    mysqli_stmt_execute($stmt);
}

function log_inscription($login, $etat) {
    /**
     * Enregistre dans les logs les inscriptions d'utilisateurs.
     *
     * @param string $login : Nom d'utilisateur.
     * @param bool $etat : statu de l'inscription.
     *
     * @return void
     */
    date_default_timezone_set("Europe/Paris");
    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "inscription OK" : "inscription KO";
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $sql = "INSERT INTO Logs (Date, Login, Statut) VALUES (?, ?, ?)";
    mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $date, $login, $statut);
    mysqli_stmt_execute($stmt);
}

function log_connexion($login, $etat) {
    /**
     * Enregistre dans les logs les connexions d'utilisateurs.
     *
     * @param string $login : Nom d'utilisateur.
     * @param bool $etat : statu de la connexion.
     *
     * @return void
     */
    date_default_timezone_set("Europe/Paris");
    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "connexion OK" : "connexion KO";
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $sql = "INSERT INTO Logs (Date, Login, Statut) VALUES (?, ?, ?)";
    mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $date, $login, $statut);
    mysqli_stmt_execute($stmt);
}

function log_deconnexion($login, $etat) {
    /**
     * Enregistre dans les logs les déconnexions d'utilisateurs.
     *
     * @param string $login : Nom d'utilisateur.
     * @param bool $etat : statu de la déconnexion.
     *
     * @return void
     */
    date_default_timezone_set("Europe/Paris");
    $date = date("Y-m-d H:i:s");
    $statut = $etat ? "deconnexion OK" : "deconnexion KO";
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $sql = "INSERT INTO Logs (Date, Login, Statut) VALUES (?, ?, ?)";
    mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $date, $login, $statut);
    mysqli_stmt_execute($stmt);
}