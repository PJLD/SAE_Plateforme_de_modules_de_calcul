<?php
session_start();
session_destroy(); // Détruire la session
header('Location: Accueil.php'); // Rediriger vers l'accueil
exit;