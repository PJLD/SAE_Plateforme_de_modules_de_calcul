<?php
require_once("../gestion/Fonctions.php");
session_start();

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
} else {
    $login = "Utilisateur inconnu";
}

log_deconnexion($login, true);
session_destroy(); // Détruire la session
header('Location: Accueil.php'); // Rediriger vers l'accueil
exit;