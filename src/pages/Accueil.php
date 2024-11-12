<?php
include("../templates/header.html");
echo"<title>accueil</title></head>";
echo"<body>";
include("../templates/navbar.html");
echo"<div class='ImageAccueil'>";
echo "<img src='../images/Image1.jpg' alt='Image Accueil 1'>";
echo "<img src='../images/Image2.webp' alt='Image Accueil 2'>";
echo "<img src='../images/Image3.jpg' alt='Image Accueil 3'>";
echo"</div>";
echo"<div style='height: 100px;'></div>";
echo"<h2>Voici une vidéo présentative de notre projet</h2>";
echo"<div class='video-container'>";
echo"<video width='600' controls autoplay muted>";
echo"<source src='../video/Video.mp4' type='video/mp4'></video>";
echo"</div>";
echo"<p>
Bonjour
<p>";
include("../templates/footer.html");