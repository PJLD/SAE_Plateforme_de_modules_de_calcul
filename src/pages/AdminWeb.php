<?php

$ok = mysqli_connect("localhost", "root", "");
$bd = mysqli_select_db($ok, "SAE");
$table = "Comptes";

$sql = "SELECT * FROM $table";

$result = mysqli_query($ok, $sql);

echo "<table>";

while($lignes = mysqli_fetch_assoc($result)){
    echo "<tr>";
    foreach($lignes as $key => $value){
        echo "<td>$value</td>";
    }
    echo "</tr>";
};
echo "</table>";