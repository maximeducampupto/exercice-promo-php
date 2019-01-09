<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 08-Jan-19
 * Time: 9:14 AM
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

// Trouver l'élève
$eleve;

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $sql = "select * from `eleves` where id=$id";

    $result = $conn->query($sql);
    $eleve = $result->fetch_assoc();
}


// Enregitrer les modifications
if (isset($_POST['update'])) {

    header('Location: index.php');

    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_STRING);
    $id = $_POST['id'];

    $sql = "update `eleves` set prenom='$prenom', nom='$nom', age='$age' where id=$id";

    $conn->query($sql);
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Modifier <?= $eleve['nom'].' '.$eleve['prenom'] ?></title>
</head>
<body>

<div class="main">
    <div id="modifierEleve" style="margin-top: 3rem;">
        <form action="update.php" method="post" class="form">
            <h3>Modifications pour <?= $eleve['nom'].' '.$eleve['prenom'] ?></h3>

            <input type="text" name="prenom" required placeholder="<?= $eleve['prenom'] ?>">

            <input type="text" name="nom" required placeholder="<?= $eleve['nom'] ?>">

            <div>
                <label for="age">Âge </label>
                <input type="number" name="age" min="1" max="300" required value="<?= $eleve['age'] ?>">
            </div>

            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

            <input type="submit" value="Enregistrer les modifications" name="update">
        </form>
    </div>
</div>

</body>
</html>
