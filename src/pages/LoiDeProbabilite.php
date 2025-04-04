<?php
session_start();
include("../templates/header.html");
require_once("../gestion/Fonctions.php");

echo"<title>Loi de Probabilité</title>
<style>
    /* Styles pour le tableau */
    table {
        width: 50%;
        border-collapse: collapse;
        margin: 50px auto;
        font-size: 18px;
    }

    td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: center;
    }

    td:first-child {
        background-color: #1c305f;
        color: white;
    }
    
    td:nth-child(2) {
        font-weight: bold;
    }

    tr:hover {
        background-color: #ddd;
    }
    .link {
    padding: 10px 20px;  
    font-size: 16px;  
    font-weight: bold;  
    color: white;  
    background-color: #1c305f;
    border: none;  
    border-radius: 5px;  
    text-decoration: none;  
    cursor: pointer;  
    transition: background-color 0.3s, transform 0.2s;
    display: block;
    width: fit-content;
    margin: 20px auto;
    }
    .link:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
</style>
</head>
<body>";

gererNavBar();

echo"
<h2>Loi Inverse-Gaussienne</h2>
<form method='post' style='max-width: 25%;'>
    <label for='mu'>L'espérance μ</label>
        <input name='mu' id='mu' placeholder='μ' required>
    <label for='lambda'>La forme λ</label>
        <input name='lambda' id='lambda' placeholder='λ' required>
    <label for='t'>t tel que P(X ≤ t) où X suit la loi inverse-gaussienne</label>
        <input name='t' id='t' placeholder='t' required>
    <label for='n'>Le nombre de sous intervalles (avec n > 0)</label>
        <input name='n' id='n' placeholder='n' required>
    <label for='methode'>Sélectionnez votre méthode de calcul</label>
        <select name='methode' id='methode'>
            <option value='Methode des trapezes'>Méthode des trapezes</option>
            <option value='Methode des rectangles'>Méthode des rectangles(médian)</option>
            <option value='Methode de Simpson'>Méthode de Simpson</option>
        </select>
    <button type='submit' name='Calculer' >Calculer</button>
</form>";




$cnx = mysqli_connect("localhost", "sae", "sae");
$bd = mysqli_select_db($cnx, "SAE");

//ajout historique
if (isset($_GET['historique'])) {
    $login = $_GET['login'];
    $date = $_GET['date'];
    $calcul = $_GET['calcul'];
    $result = $_GET['result'];

    $sql = "INSERT INTO Historique (login, DateHistorique, calcul, resultat) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($cnx, $sql);

    mysqli_stmt_bind_param($stmt, "ssss", $login, $date, $calcul, $result);
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green; text-align: center;'>Le résultat a été enregistré dans votre historique</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erreur lors de l'enregistrement du résultat dans votre historique</p>";
    }
    mysqli_stmt_close($stmt);
}

//traitement du calcul (bouton 'Calculer')
if (isset($_POST['Calculer'])) {
    $result="";
    $a=0.1;
    $mu = $_POST['mu'];
    $lambda = $_POST['lambda'];
    $b = $_POST['t']; //correspond au champ de t
    $n = $_POST['n'];
    $calcul = $_POST['methode'];

    $login = $_SESSION['login'];

    date_default_timezone_set("Europe/Paris");
    $date = (new DateTime())->format("d/m/Y H:i:s");


    if (!is_numeric($mu) || !is_numeric($lambda) || !is_numeric($b) || !is_numeric($n)) {
        echo "<div class='resultatConteneur'>";
        echo "<h3>Résultat du calcul : $calcul</h3>";
        echo "<p style='color: red;'>Erreur : Toutes les valeurs doivent être des nombres (entiers ou flottants).</p>";
        echo "</div>";
        include("../templates/footer.html");
        exit;
    }

    if ($b <= 0 || $n <= 0 || $mu <= 0 || $lambda <= 0) {
        $result=  "Vérifier que tout vos paramètres soient strictement positifs";
        echo "<div class='resultatConteneur'>";
        echo "<h3>Résultat du calcul : $calcul</h3>
        <p style='color: red;'>Erreur : ".$result."</p>";
        echo "</div>";
    }else if ($calcul == 'Methode de Simpson' && $n%2==1){
        $result= "Vous avez choisi la méthode de Simpson, donc ajuster un nombre de sous-intervalles pair";
        echo "<div class='resultatConteneur'>";
        echo "<h3>Résultat du calcul : $calcul</h3>
        <p style='color: red;'>Erreur : ".$result."</p>";
        echo "</div>";
    }


    else{
        if ($calcul =='Methode des trapezes') {
            $result = methodeDesTrapezes($a, $b, $mu, $lambda,$n);
        }elseif ($calcul =='Methode des rectangles') {
            $result = methodeDesRectangles($a, $b, $mu, $lambda,$n);
        }elseif ($calcul =='Methode de Simpson') {
            $result = methodeDeSimpson($a, $b,$mu,$lambda,$n);
        }
        $sigma = sqrt(pow($mu,3) / $lambda);

        $resultats = [
            'Résultat' => $result,
            'Lambda' => $lambda,
            'Moyenne'=> $mu,
            'Ecart-type' => $sigma,
            'Méthode' => $calcul
        ];


        echo "<table class='tableau-resultats'>";
        echo "<tbody>";
        foreach ($resultats as $parametre => $valeur) {
            echo "<tr>";
            echo "<td>".$parametre."</td>";
            echo '<td>'.$valeur."</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";

        echo "<a href='?historique=true&login=".$login."&date=".$date."&calcul=".$calcul."&result=".$result."' class='link'>Ajouter à l'historique</a>";

        echo "<div class='resultatConteneur'>";
        echo "<h3>Résultat du calcul : $calcul</h3>
    <p><strong>$result</strong></p>";
        echo "</div>";

        // Calcul des points pour le graphique
        $points_x = [];
        $points_y = [];
        $inter = ($b - $a) / $n;
        for ($i = 0; $i <= $n; $i++) {
            $x = $a + $i * $inter; //x
            $y = loiInverseGaussienne($x, $lambda, $mu); //f(x)
            $points_x[] = $x;
            $points_y[] = $y;
        }






        // Affichage du graphique
        echo "
    <div style='display: flex; justify-content: center; margin-top: 30px;'>
        <canvas id='graphCanvas' style='width: 600px; height: 400px;'></canvas>
    </div>";

        echo "
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script> <!--importation de chart.js-->
    <script>
    var ctx = document.getElementById('graphCanvas'); 
    new Chart(ctx, {
        type: 'line',   //graphique courbe ligne
        data: {
            labels: " . json_encode($points_x) . ",  //abscisse
            datasets: [{
                label: 'Loi Inverse-Gaussienne',
                data: " . json_encode($points_y) . ",  //ordonnée
                fill: true,
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                borderColor: 'rgba(0, 0, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Valeurs de la variable aléatoire (x)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Densité de probabilité f(x)'
                    }
                }
            }
        }
    });
    </script>
    ";
    }
}

include("../templates/footer.html");