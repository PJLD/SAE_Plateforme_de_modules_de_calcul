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
<div class='TexteExplicatif'>
<h1>Bienvenue sur Calcub, notre plateforme de calcul en ligne</h1>
<p>Notre plateforme a été conçue pour simplifier vos calculs quotidiens et vous offrir une expérience utilisateur optimale. Que vous soyez un particulier ou un professionnel, vous trouverez ici différents modules de calculs adaptés à vos besoins.</p> 
<p>Vous pouvez facilement accéder aux fonctionnalités suivantes :</p> 
<ul>
   <li><strong>Modules de calculs</strong> : Une sélection d'outils permettant de réaliser divers calculs axés sur la probabilité</li>
   <li><strong>Gestion de votre profil</strong> : Créez un compte, modifiez vos informations et profitez de toutes nos fonctionnalités.</li>
</ul>
<p>Nous vous invitons à vous inscrire pour profiter pleinement de toutes les fonctionnalités de notre plateforme. Une fois inscrit, vous aurez accès à un tableau de bord personnalisé où vous pourrez accéder à vos modules de calculs.</p>
<p>N'hésitez pas à explorer et à vous inscrire pour bénéficier de tous les outils que nous mettons à votre disposition !</p>
<p>(L'équipe de développement du site)</p>
</div>
<div style='height: 50px;'></div>
<div class='video-container'>
<video width='600' controls autoplay muted>
<source src='../video/Video.mp4' type='video/mp4'></video>
</div>
</div>";
include("../templates/footer.html");