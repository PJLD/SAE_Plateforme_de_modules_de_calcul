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

/**
 * Implémente l'algorithme RC4 pour chiffrer ou déchiffrer des données.
 *
 * @param string $cle La clé de chiffrement utilisée pour initialiser l'algorithme.
 * @param string $donnees Les données à chiffrer ou à déchiffrer.
 * @return string Les données chiffrées ou déchiffrées.
 */
function rc4($cle, $donnees) {
    $s = range(0, 255); // Initialisation du tableau S avec les valeurs de 0 à 255
    $j = 0;
    $longueurCle = strlen($cle);
    $longueurDonnees = strlen($donnees);

    if ($longueurCle<=0 || $longueurDonnees<=0){
        return "La clé ou les données sont vides";
    }

    // Initialisation de la permutation S avec la clé
    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $s[$i] + ord($cle[$i % $longueurCle])) % 256;
        list($s[$i], $s[$j]) = [$s[$j], $s[$i]]; // Échange des valeurs
    }


    // Génération du flux de clé et chiffrement/déchiffrement
    $i = $j = 0;
    $sortie = '';
    $longueurDonnees = strlen($donnees);


    for ($k = 0; $k < $longueurDonnees; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        list($s[$i], $s[$j]) = [$s[$j], $s[$i]]; // Échange des valeurs


        $t = ($s[$i] + $s[$j]) % 256;
        $octetFluxCle = $s[$t];


        $sortie .= chr(ord($donnees[$k]) ^ $octetFluxCle); // Opération XOR avec le flux de clé
    }


    return $sortie;
}


/**
 * Chiffre une chaîne de caractères avec RC4 et retourne le résultat en hexadécimal.
 *
 * @param string $cle La clé de chiffrement utilisée.
 * @param string $texteClair Le texte en clair à chiffrer.
 * @return string Le texte chiffré sous forme hexadécimale.
 */
function rc4_chiffrer($cle, $texteClair) {
    return bin2hex(rc4($cle, $texteClair));
}


/**
 * Déchiffre une chaîne de caractères en hexadécimal avec RC4.
 *
 * @param string $cle La clé de déchiffrement (doit être la même que pour le chiffrement).
 * @param string $texteChiffre Le texte chiffré sous forme hexadécimale.
 * @return string Le texte déchiffré en clair.
 */
function rc4_dechiffrer($cle, $texteChiffre) {
    return rc4($cle, hex2bin($texteChiffre));
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
    for ($i = 1; $i < $n; $i++) {
        $sum += loiInverseGaussienne($a + $i * $h, $mu, $lambda);
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
    $sum = 0;
    for ($i = 0; $i < $n; $i++) {
        $sum += loiInverseGaussienne($a + ($i + 0.5) * $h, $mu, $lambda);
    }
    return $sum * $h;
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
        echo"Le nombre de subdivisions doit etre pair"; // Simpson's rule requires an even number of intervals
    }
    $h = ($b - $a) / $n;
    $sum = loiInverseGaussienne($a, $mu, $lambda) + loiInverseGaussienne($b, $mu, $lambda);
    for ($i = 1; $i < $n; $i++) {
        if ($i % 2 == 0) {
            $sum += 2 * loiInverseGaussienne($a + $i * $h, $mu, $lambda);
        } else {
            $sum += 4 * loiInverseGaussienne($a + $i * $h, $mu, $lambda);
        }
    }
    return $sum * $h / 3;
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
    $date = (new DateTime())->format("d/m/Y H:i:s");
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
    $date = (new DateTime())->format("d/m/Y H:i:s");
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
    $date = (new DateTime())->format("d/m/Y H:i:s");
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
    $date = (new DateTime())->format("d/m/Y H:i:s");
    $statut = $etat ? "deconnexion OK" : "deconnexion KO";
    $cnx = mysqli_connect("localhost", "sae", "sae");
    $sql = "INSERT INTO Logs (Date, Login, Statut) VALUES (?, ?, ?)";
    mysqli_select_db($cnx, "SAE");
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $date, $login, $statut);
    mysqli_stmt_execute($stmt);
}