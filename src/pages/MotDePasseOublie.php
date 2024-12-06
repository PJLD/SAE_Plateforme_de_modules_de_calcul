<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Mot de Passe Oublie</title></head>
<body>";
gererNavBar();
echo"<div style='height: 80px;'></div>
<div style='text-align: center; margin: 20px 0;'>
    <img src='../images/Construction.png' alt='Image Construction Site Web'>
</div>
<div class='TexteExplicatif'>
    <h1>Mot de passe oublié ?</h1>
    <p>Cette page est actuellement en cours de construction. Nous travaillons activement pour vous offrir un service qui vous permettra de récupérer facilement votre mot de passe.</p>
    <p>Revenez bientôt pour accéder à cette fonctionnalité.</p>
    <p>Merci pour votre patience et votre compréhension.</p>
    <p>(L'équipe de développement du site)</p>
</div>";
include("../templates/footer.html");