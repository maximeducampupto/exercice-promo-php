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

function getAllEleves($conn)
{
    $sql = 'select * from `eleves`';
    $array = [];

    if ($result = $conn->query($sql)) {

        while ($row = $result->fetch_assoc()) {

            array_push($array, $row);
        }

        $result->close();
    }

    return $array;
};


function ajouterEleve($prenom, $nom, $age, $conn)
{
    $sql = "INSERT INTO `eleves` (`id`, `prenom`, `nom`, `age`) VALUES (NULL, '$prenom', '$nom', '$age');";

    $conn->query($sql);

    header('Location: index.php');
    exit();

}

function supprimerEleve($id, $conn)
{
    $sql = "delete from eleves where id = '$id'";
    $conn->query($sql);

    header('Location: index.php');
    exit();
}

function ajouterMug($description, $eleve_id, $conn)
{
    $sql = "INSERT INTO `mugs` (`id`, `description`) VALUES (NULL, '$description');";
    $conn->query($sql);

    $lastMugID = $conn->insert_id;

    majJoints($lastMugID, $eleve_id, $conn);

}

function majJoints($mug_id, $eleve_id, $conn)
{
    $sql = "INSERT INTO `eleves_mugs` (`id`, `id_eleve`, `id_mug`) VALUES (NULL, '$eleve_id', '$mug_id');";
    $conn->query($sql);

    header('Location: index.php');

    exit();
}



// Affiche tous les élèves IMPORTANT
$eleves = getAllEleves($conn);

if (isset($_GET['prenom']))
{

    $prenom = filter_var($_GET['prenom'], FILTER_SANITIZE_STRING);
    $nom = filter_var($_GET['nom'], FILTER_SANITIZE_STRING);
    $age = $_GET['age'];


    ajouterEleve($prenom, $nom, $age, $conn);
}

// Supprimer un élève

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['supprimerEleve']))
{
    $id = $_POST['eleveASupprimer'];

    supprimerEleve($id, $conn);
}

// Ajouter un mug

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['ajouter-mug'])) {

    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $eleve_id = filter_var($_POST['eleve-id'], FILTER_SANITIZE_STRING);


   ajouterMug($description, $eleve_id, $conn);
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
    <title>Up To Fourmies, promo 2018/2019</title>
</head>
<body>

<header class="main-header">
    <h1>Up To Fourmies, promo 2018/2019</h1>
</header>

<div class="main">

    <div class="elevesContainer">
        <?php

        foreach($eleves as $eleve)
        {
           ?>
                <div class="eleve">
                    <p>Nom: <b><?= $eleve['nom']?></b></p>
                    <p>Prénom: <b><?= $eleve['prenom']?></b></p>
                    <p>Âge: <b><?= $eleve['age']?></b></p>

                    <div class="modifications">
                        <form action="index.php" method="post">
                            <input type="submit" name="supprimerEleve" value="Supprimer">
                            <input type="hidden" name="eleveASupprimer" value="<?= $eleve['id'] ?>">
                        </form>
                        <a href="<?= 'update.php?id='.$eleve['id'] ?>">Modifier</a>
                        <a href="<?= 'mugs.php?id='.$eleve['id'] ?>" class="mug">Mugs</a>
                    </div>
                </div>
            <?php
        }

        ?>
    </div>


    <div id="ajouterEleve">
        <form action="index.php" method="get" class="form">
            <h3>Ajouter un élève</h3>

            <input type="text" name="prenom" required placeholder="Prénom">

            <input type="text" name="nom" required placeholder="Nom ">

            <div>
                <label for="age">Âge </label>
                <input type="number" name="age" min="1" max="300" required>
            </div>

            <input type="submit" value="Ajouter">
        </form>
    </div>

    <div id="ajouterMug">
        <form action="index.php" method="post" class="form">
            <h3>Ajouter un mug</h3>

            <input type="text" name="description" required placeholder="Description">

            <select name="eleve-id" required>
                <?php
                foreach($eleves as $eleve) { ?>

                    <option value="<?= $eleve['id'] ?>">
                        <?= $eleve['prenom'].' '.$eleve['nom']?>
                    </option>

                <?php } ?>
            </select>

            <input type="submit" value="Ajouter" name="ajouter-mug">
        </form>
    </div>

</div>


</body>
</html>
