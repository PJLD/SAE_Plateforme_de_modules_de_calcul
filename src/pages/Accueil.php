<?php
include("../templates/header.html");
echo"<title>accueil</title></head>
<body>";
include("../templates/navbar.html");
echo"<div class='ImageAccueil'>
<img src='../images/Image1.jpg' alt='Image Accueil 1'>
<img src='../images/Image2.webp' alt='Image Accueil 2'>
<img src='../images/Image3.jpg' alt='Image Accueil 3'>
</div>
<div style='height: 100px;'></div>
<h2>Voici une vidéo présentative de notre projet</h2>
<div class='video-container'>
<video width='600' controls autoplay muted>
<source src='../video/Video.mp4' type='video/mp4'></video>
</div>
<p>
Bonjour
<p>";
include("../templates/footer.html");