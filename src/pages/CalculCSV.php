<!DOCTYPE html>
<html lang="fr">C
<head>
    <meta charset="UTF-8">
    <title>Calcul via CSV</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/moncss.css">
</head>
<body>
<div class="header">
    <img src="../images/Logo.png" alt="Logo du site" class="logo">
    <h1>CALCUB</h1>
</div>
<nav>
    <ol>
        <li><a href="Accueil.php">Accueil</a></li>
        <li><a href="ModuleDeCalcul.html">Module de Calcul</a></li>
        <li><a href="CalculCSV.html">Calculer via CSV</a></li>
        <li><a href="Contacts.html">Contacts</a></li>
        <li><a href="Login.html"><button type="button" class="login">Login</button></a></li>
    </ol>
</nav>
<h2>Calculer à partir d'un fichier CSV</h2>
<form method='post'>
    <label for="csvFile">Importez votre fichier CSV :</label>
    <input type="file" id="csvFile" name="csvFile" accept=".csv">
    <button type='submit' name='OK' >OK</button>
    <label for="calcul">Sélectionnez votre calcul</label>
    <select name="calcul" id="calcul">
        <option value="moyenne">Moyenne</option>
        <option value="écarttype">Ecart-type</option>
        <option value="esperance">Esperance</option>
        <option value="variance">Variance</option>
    </select>
    <button type='submit' name='Calculer' >Calculer</button>
</form>
<footer>
    <h1 style="padding-top: 30px;">Membres de l'équipe</h1>
    <p>INF2-FI-A</p>
    <div style="padding-bottom: 30px;">
        <p>Bilong Noa - Colombani Esteban - Da Silva Luca - Juillard Pierre - Tramier Joseph</p>
    </div>
</footer>
</body>
</html>