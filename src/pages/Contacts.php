<?php
include("../templates/header.html");
echo"<title>accueil</title></head>";
echo"<body>";
include("../templates/navbar.html");
echo"<h2>Voici une vidéo présentative de notre projet</h2>";
echo"<div class='video-container'>";
echo"<video width='600' controls autoplay muted>";
echo"<source src='../video/Video.mp4' type='video/mp4'></video>";
echo"</div>";
echo"<div style='height: 50px;'></div>";
echo"<div class='contacts-list'>";
echo"<h2 style='font-size: 20px;'>Vous pouvez nous contacter via nos adresses mail respectives : </h2>";
echo"<ul>";
echo"<li>bilong.noa@gmail.com</li>";
echo"<li>c17esteban@gmail.com</li>";
echo"<li>lukds.dasilva@gmail.com</li>";
echo"<li>pierrejld2005@gmail.com</li>";
echo"<li>joseph.tramier1@gmail.com</li>";
echo"</ul>";
echo"</div>";
include("../templates/footer.html");