<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        if(isset($_POST['button'])){
            // Récupérer les données du formulaire
            $BrancheName = $_POST['Branche'];
            $Nom_Bureau = $_POST['Nom_Bureau'];
            $Utilisateur = $_POST['Utilisateur'];
            $Machine = $_POST['Machine'];
            $Marque = $_POST['Marque'];
            $Modele = $_POST['Modele']; // Utilisez le nom correct de la colonne
            $NSerie = $_POST['NSerie'];

            // Vérifier si tous les champs obligatoires sont remplis
            if(!empty($BrancheName) && !empty($Nom_Bureau) && !empty($Utilisateur) && !empty($Machine) && !empty($Marque) && !empty($Modele) && !empty($NSerie)){
                include_once "connexion.php";

                // Vérifier si la branche existe dans la table departement
                $checkBranchQuery = "SELECT id_branche FROM departement WHERE Branche = ?";
                $stmtCheck = mysqli_prepare($con, $checkBranchQuery);
                mysqli_stmt_bind_param($stmtCheck, "s", $BrancheName);
                mysqli_stmt_execute($stmtCheck);
                mysqli_stmt_store_result($stmtCheck);

                if(mysqli_stmt_num_rows($stmtCheck) > 0) {
                    // La branche existe, on peut ajouter la machine
                    mysqli_stmt_close($stmtCheck);

                    // Obtenir l'ID de la branche
                    $branchQuery = "SELECT id_branche FROM departement WHERE Branche = ?";
                    $stmt = mysqli_prepare($con, $branchQuery);
                    mysqli_stmt_bind_param($stmt, "s", $BrancheName);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $BrancheID);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    // Utiliser des requêtes préparées pour éviter les injections SQL
                    $query = "INSERT INTO machines (Branche, `Nom Bureau`, `Utilisateur`, `Machine`, `Marque`, `Modéle`, `N°Serie`) VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "sssssss", $BrancheID, $Nom_Bureau, $Utilisateur, $Machine, $Marque, $Modele, $NSerie);
                    $result = mysqli_stmt_execute($stmt);

                    if($result){
                        header("location: index.php");
                        exit(); // Ajouter exit pour arrêter l'exécution du script après la redirection
                    }
                    else{
                        $message = "Machine non ajoutée : " . mysqli_error($con); // Afficher l'erreur MySQL
                    }

                    mysqli_stmt_close($stmt); // Fermer la requête préparée
                } else {
                    // La branche n'existe pas, afficher un message d'erreur
                    $message = "La branche spécifiée n'existe pas dans la table departement.";
                }

                mysqli_close($con); // Fermer la connexion à la base de données
            } else {
                $message = "Veuillez remplir tous les champs !!";
            }
        }
    ?>
    <div class="form">
        <a href="index.php" class="back_btn"><img src="images/retour.png"> Retour</a>
        <h2>Ajouter une machine</h2>
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
                        while ($row = mysqli_fetch_assoc($branchResult)) {
                           echo '<option value="' . $row['Branche'] . '">' . $row['Branche'] . '</option>';
                           
                        }
                    } else {
                        // Print an error message if the query fails or no data is found
                        echo '<option disabled>Error fetching branches</option>';
                    }

                    mysqli_close($con); // Close the database connection
                    ?>
                </select>
            </div>
            
            <!-- Autres champs du formulaire -->
            <label for="Nom_Bureau">Nom Bureau</label>
            <input type="text" name="Nom_Bureau" id="Nom_Bureau">
            <label for="Utilisateur">Utilisateur</label>
            <input type="text" name="Utilisateur" id="Utilisateur">
            <label for="Machine">Machine</label>
            <div class="select">
                <select name="Machine" id="Machine">
                    <option selected disabled>Choisissez une machine</option>
                    <option value="Boitier">Unité Centrale <img src="images/boitier.png" alt=""></option>
                    <option value="Ecran">Ecran <img src="images/ecran.png" alt=""></option>
                    <option value="Imprimante">Imprimante <img src="images/imprimante.png" alt=""></option>
                    <option value="Scanner">Scanner <img src="images/scanner.png" alt=""></option>
                    <option value="IP Phone">IP Phone <img src="images/phone.png" alt=""></option>
                    <option value="Photocopieuse">Photocopieuse <img src="" alt=""></option>
                </select>
            </div>
            <label for="Marque">Marque</label>
            <input type="text" name="Marque" id="Marque">
            <label for="Modele">Modèle</label> 
            <input type="text" name="Modele" id="Modele">
            <label for="NSerie">N°Serie</label>
            <input type="text" name="NSerie" id="NSerie">
            <input type="submit" value="Ajouter" name="button">
        </form>
    </div>
</body>
</html>
