<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
        include_once "connexion.php";
        $id = $_GET['id'];
        $req = mysqli_query($con , "SELECT * FROM machines WHERE id = $id");
        $row = mysqli_fetch_assoc($req);
        //verifier que le bouton ajouter a bien ete cliqué
        if(isset($_POST['button'])){
            //extraction des informations envoye dans des variables par la methode post
            extract($_POST);
            //verifier que tout les champs ont ete remplis
            if(isset($Branche) && isset($Nom_Bureau) && isset($Utilisateur) && isset($Machine) && isset($Marque) && isset($Modéle) && isset($N°Serie)){
                $req = mysqli_query($con, "UPDATE machines SET Branche = '$Branche', `Nom Bureau` = '$Nom_Bureau', Utilisateur = '$Utilisateur', Machine = '$Machine', Marque = '$Marque', Modéle = '$Modéle', `N°Serie` = '$N°Serie' WHERE id = $id");

                if($req){
                    header("location: index.php");
                }
                else{
                    $message = "Machine non modifiée";
                }
            }else{
                $message = "Veuillez remplir tous les champs!!";
            }
        }
    ?>

    <div class="form">
        <a href="index.php" class="back_btn"><img src="images/retour.png"> Retour</a>
        <h2>Modifier la machine : <?=$row['Machine']?> </h2>
        <p class="erreur_message">
            <?php 
                if(isset($message)){
                    echo $message;
                } 
            ?>
        </p>
        <form action="" method="POST">
            <label for="Branche">Branche</label>
            <div class="select">
                <select name="Branche" id="Branche">
                    <option selected disabled>Choisissez une branche</option>
                    <!-- Populate options dynamically from your "departement" table -->
                    <?php
                    // Include your database connection
                    include_once "connexion.php";

                    // Query to fetch branches from the "departement" table
                    $branchQuery = "SELECT * FROM departement";
                    $branchResult = mysqli_query($con, $branchQuery);

                    // Check if the query was successful
                    if ($branchResult && mysqli_num_rows($branchResult) > 0) {
                        // Loop through the results and generate options
                        while ($branchRow = mysqli_fetch_assoc($branchResult)) {
                            $selected = ($branchRow['id_branche'] == $row['Branche']) ? 'selected' : '';
                            echo '<option value="' . $branchRow['id_branche'] . '" ' . $selected . '>' . $branchRow['Branche'] . '</option>';
                        }
                    } else {
                        // Print an error message if the query fails or no data is found
                        echo '<option disabled>Error fetching branches</option>';
                    }

                    mysqli_close($con); // Close the database connection
                    ?>
                </select>
            </div>
            <label for="Nom_Bureau">Nom Bureau</label>
            <input type="text" name="Nom_Bureau" id="Nom_Bureau" value="<?=$row['Nom Bureau']?>">
            <label for="Utilisateur">Utilisateur</label>
            <input type="text" name="Utilisateur" id="Utilisateur" value="<?=$row['Utilisateur']?>">
            <label for="Machine">Machine</label>
            <div class="select">
                <select name="Machine" id="Machine">
                    <option selected disabled>Choisissez une machine</option>
                    <option value="Boitier" <?=($row['Machine'] == 'Boitier') ? 'selected' : ''?>>Boitier</option>
                    <option value="Ecran" <?=($row['Machine'] == 'Ecran') ? 'selected' : ''?>>Ecran</option>
                    <option value="Imprimante" <?=($row['Machine'] == 'Imprimante') ? 'selected' : ''?>>Imprimante</option>
                    <option value="Scanner" <?=($row['Machine'] == 'Scanner') ? 'selected' : ''?>>Scanner</option>
                    <option value="IP Phone" <?=($row['Machine'] == 'IP Phone') ? 'selected' : ''?>>IP Phone</option>
                    <option value="Photocopieuse" <?=($row['Machine'] == 'Photocopieuse') ? 'selected' : ''?>>Photocopieuse</option>
                </select>
            </div>
            <label for="Marque">Marque</label>
            <input type="text" name="Marque" id="Marque" value="<?=$row['Marque']?>">
            <label for="Modele">Modèle</label> 
            <input type="text" name="Modéle" id="Modéle" value="<?=$row['Modéle']?>">
            <label for="NSerie">N°Serie</label>
            <input type="text" name="N°Serie" id="NSerie" value="<?=$row['N°Serie']?>">
            <input type="submit" value="Modifier" name="button">
        </form>
    </div>
</body>
</html>
