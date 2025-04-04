<?php
require_once("../gestion/Fonctions.php");
session_start();

//récupération du login
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
} else {
    $login = "Utilisateur inconnu";
}

$ip = $_SERVER['REMOTE_ADDR'];
log_deconnexion($ip, $login, true);
session_unset();
session_destroy(); // Détruire la session

session_start(); //relancer la session

header('Location: Accueil.php'); // Rediriger vers l'accueil
exit;