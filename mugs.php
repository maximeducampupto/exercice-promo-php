<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 08-Jan-19
 * Time: 2:47 PM
 */

$servername = "";
$username = "";
$password = "";
$dbname = "";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
    $conn->select_db($dbname);
}

$id = $_GET['id'];

function nombreMugs($mugs)
{
    return count($mugs) > 1 ? 'mugs' : 'mug';
}


function getMugs($eleve_id, $conn)
{
    $sql = "select mugs.description from eleves_mugs, mugs where eleves_mugs.id_eleve = '$eleve_id' and eleves_mugs.id_mug = mugs.id";
    $result = $conn->query($sql);

    $array = [];
    while ($row = $result->fetch_assoc()) {

        array_push($array, $row);
    }

    return $array;

}

function getEleve($id, $conn)
{
    $sql = "select * from eleves where id=$id";

    $result = $conn->query($sql);
    return $result->fetch_assoc();

}

$mugs = getMugs($id, $conn);
$eleve = getEleve($id, $conn);

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>

<div class="container">

    <h1>Les mugs de <?= $eleve['prenom'].' '.$eleve['nom']?></h1>

   <div class="eleve_mug">

       <h3><?= $eleve['prenom'].' a     '. count($mugs) . ' '. nombreMugs($mugs);?></h3>

       <div class="mugsContainer">

           <ul>
           <?php

           foreach($mugs as $mug) { ?>

               <li>- <?= $mug['description']?></li>

          <?php } ?>
           </ul>
       </div>
   </div>

    <a href="index.php">Retour</a>
</div>

</div>
</body>
</html>