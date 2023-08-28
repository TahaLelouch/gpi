<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion De Parc Informatique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="header">
            <img src="images/MJ-Maroc.png" alt="Special Image" class="special-image">
            <h1>Gestion de Parc Informatique de la Cour d'Appel de Tétouan</h1>
        
            <div class="search-container">
                <input type="text" id="user-search" placeholder="Rechercher...">
                <button id="search-button"><img class="search-img" src="images/rech.png"></button>
            </div>
        </div> 
    </nav>
       
    <div class="container">
        <a href="Ajouter.php" class="Btn_add"> <img src="images/add.png"> Ajouter</a>
        
        <table>
            <tr id="header-row">
                <th colspan="9"></th>
            </tr>
            <tr>
                <th>Branche</th>
                <th>Nom Bureau</th>
                <th>Utilisateur</th>
                <th>Machine</th>
                <th>Marque</th>
                <th>Modéle</th> <!-- Correction du nom de la colonne ici -->
                <th>N°Serie</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php
                // inclure la page de connexion
                include_once "connexion.php";
                // requête pour afficher la liste des machines triée par nom d'utilisateur
                $req = mysqli_query($con , "SELECT machines.*, departement.Branche AS BrancheName FROM machines LEFT JOIN departement ON machines.Branche = departement.id_branche ORDER BY Utilisateur ASC");
                if(mysqli_num_rows($req) == 0){
                    echo "Il n'y a pas encore de machines à ajouter!!";
                } else {
                    while($row=mysqli_fetch_assoc($req)){
                        ?>
                            <tr class="data-row">
                                <td><?=$row['BrancheName']?></td>
                                <td><?=$row['Nom Bureau']?></td>
                                <td><?=$row['Utilisateur']?></td>
                                <td><?=$row['Machine']?></td>
                                <td><?=$row['Marque']?></td>
                                <td><?=$row['Modéle']?></td> <!-- Correction du nom de la colonne ici -->
                                <td><?=$row['N°Serie']?></td>
                                <td><a href="modifier.php?id=<?=$row['id']?>"> <img src="images/edit.png"></a></td>
                                <td><a href="supprimer.php?id=<?=$row['id']?>"> <img src="images/trash.png"></a></td>
                            </tr>
                        <?php 
                    }
                }
            ?>
        </table>
    </div>  

    <script>
        document.getElementById('search-button').addEventListener('click', search);
        document.getElementById('user-search').addEventListener('keydown', function (event) {
            if (event.key === "Enter") {
                search();
            }
        });

        function search() {
            var searchValue = document.getElementById('user-search').value.toLowerCase();
            var rows = document.querySelectorAll('.data-row');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellText = cells[j].textContent.toLowerCase();

                    if (cellText.indexOf(searchValue) > -1) {
                        found = true;
                        break; // Si une correspondance est trouvée dans cette ligne, pas besoin de continuer à vérifier les autres cellules.
                    }
                }

                if (found) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
