<?php
session_start();
require_once("../gestion/Fonctions.php");
include("../templates/header.html");
echo"<title>Contacts</title></head>
<body>";
gererNavBar();
echo"<div style='height: 50px;'></div>
<div class='contacts-list'>
<h2 style='font-size: 20px;'>Vous pouvez nous contacter via nos adresses mail respectives : </h2>
<ul>
<li>bilong.noa@gmail.com</li>
<li>c17esteban@gmail.com</li>
<li>lukds.dasilva@gmail.com</li>
<li>pierrejld2005@gmail.com</li>
<li>joseph.tramier1@gmail.com</li>
</ul>
</div>";
include("../templates/footer.html");