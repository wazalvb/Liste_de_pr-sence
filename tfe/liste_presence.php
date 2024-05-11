<?php
   @include 'config.php';

   // Vérifier si la session est déjà démarrée
   if (session_status() == PHP_SESSION_NONE) {
       session_start();
   }

   // Initialisation de la variable $id avec une valeur par défaut
   if (!isset($_SESSION['user_id']) && !isset($_SESSION['isAdmin'])) {
       header('location:login.php');
       exit(); // Assurez-vous d'arrêter le script après la redirection
   }

   $jour = isset($_GET['jour']) ? $_GET['jour'] : "lundi";
   $classe = isset($_GET['classe']) ? $_GET['classe'] : "6TTI";
   $id = $_SESSION['user_id'];

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Traitement des données du formulaire...

       // Redirection pour éviter la soumission multiple du formulaire
       header("Location: " . $_SERVER['PHP_SELF']);
       exit();
   }

   // Utilisation des requêtes préparées pour une meilleure sécurité
   $heures_modifiables = [];
   $query = $conn->prepare("SELECT heure FROM horaire_profs WHERE id_prof=? AND jour=? AND classe=?");
   $query->bind_param("sss", $id, $jour, $classe); // 's' spécifie le type 'string'
   $query->execute();
   $result = $query->get_result();

   if ($result->num_rows > 0) {
       while ($obj = $result->fetch_object()) {
           $heures_modifiables[] = $obj->heure;
       }
   }

   $query->close();

   $presenceData = [];
   $presenceQuery = "SELECT p.id_eleve, p.heure, p.etat, s.classe
                  FROM presences p
                  JOIN student s ON p.id_eleve = s.ID
                  WHERE p.heure >= 1 AND p.heure <= 10 AND p.jour = '$jour' AND s.classe = '$classe'";
   $presenceResult = $conn->query($presenceQuery);

   if ($presenceResult->num_rows > 0) {
       while ($row = $presenceResult->fetch_assoc()) {
           $presenceData[$row['id_eleve']][$row['heure']] = $row['etat'];
       }
   }

    // echo "<pre>";
    // print_r($presenceData);
    // echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
   <title>Liste des Présences</title>
   <style>
        body {
           font-family: Arial, sans-serif;
           background-color: #EBF5FB ;
           margin: 0;
           padding: 0;
           text-align: center;
       }
       h2 {
           text-align: center;
           margin-top: 20px;
       }
       table {
           border-collapse: collapse;
           width: 100%;
           max-width: 600px; /* Réduction de la largeur de la table */
           margin: 20px auto;
           background-color: #fff;
           border: 1px solid #ddd;
       }
       th, td {
           padding: 8px;
           text-align: center;
       }
       th {
           background-color: #f2f2f2;
           border-bottom: 2px solid #ddd;
       }
       td {
        vertical-align: top;
           border-bottom: 1px solid #ddd;
       }
       tr {
            height: 100px;
       }
       .presence {
           cursor: pointer;
           margin: 0 5px;
           color: #333;
           padding: 5px 8px;
           border-radius: 4px;
           display: block;
       }
       .presence:hover {
           color: #fff;
       }
       .presence-buttons {
           display: flex;
           justify-content: center;
       }
       input[type="submit"], input[type="button"] {
           padding: 10px 20px;
           margin: 10px;
           color: #212F3C;
           border: none;
           border-radius: 4px;
           cursor: pointer;
           background-color: #AEB6BF;
       }
       input[type="submit"]:hover, input[type="button"]:hover {
           filter: brightness(85%); /* Réduction de la luminosité au survol */
       }
       .present-btn:checked + .presence { background-color: #4CAF50; }
       .retard-btn:checked + .presence { background-color: #FFD700; }
       .absent-btn:checked + .presence { background-color: #FF6347; }
       .submit-btn {
        text-align: center;
        width: 100px;
       }
       .disabled { opacity: 0; }
       .hidden { display: none; }

       .button {
        display: inline-block;
        margin: 10px; /* Espace entre les boutons */
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        color: #ffffff;
        background-color: #007bff; /* Couleur de fond */
        border: 1px solid #007bff; /* Bordure */
        border-radius: 5px; /* Coins arrondis */
        transition: background-color 0.3s, color 0.3s; /* Transition fluide lors du survol */
        }

        .button:hover {
            background-color: #0056b3; /* Couleur de fond au survol */
            border-color: #0056b3; /* Bordure au survol */
        }

        .return-home {
            background-color: #dc3545;
            border-color: #c82333; /* Bordure au survol */
            margin-top: 20px; /* Marge en haut */
            display: inline-block; /* Affichage en bloc pour occuper toute la largeur */
            color: white; /* Couleur rouge */
            margin-left: auto;
            margin-right: auto;
        }

        .return-home:hover {
            background-color: #c82333;
            border-color: #c82333; /* Bordure au survol */
        }

        .legend {
            margin-top: 20px;
            text-align: center;
        }

        .legend span {
            display: inline-block;
            margin: 0 10px;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .present {
            background-color: #4CAF50; /* Vert */
            color: white;
        }

        .late {
            background-color: #FFD700; /* Jaune */
            color: black;
        }

        .absent {
            background-color: #FF6347; /* Rouge */
            color: white;
        }

    </style>
</head>

<body>

   <main>
   <h2>Liste des Présences</h2>

    <?php
    
        echo '<table>';
        echo '<tr>';
        echo '<th>Nom de l\'élève</th>';

        // Affichage des colonnes pour chaque heure de cours de 1 à 10
        for ($heure = 1; $heure <= 8; $heure++) {
            echo "<th>$heure</th>";
        }
        echo '</tr>';

        // Vérification de la connexion
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données: " . $conn->connect_error);
        }

        // Requête pour récupérer les noms des élèves de la classe "6TTI"
        $studentsQuery = "SELECT s.ID, s.prenom, s.nom FROM student s WHERE s.classe = '$classe'";
        $studentResult = $conn->query($studentsQuery);

        // Boucle sur les élèves
        if(!$_SESSION['isAdmin']){
        while ($row = $studentResult->fetch_assoc()) {
            echo "<tr><td>" . $row['prenom'] . " " . $row['nom'] . "</td>";

            // Boucle sur chaque heure de cours
            for ($heure = 1; $heure <= 8; $heure++) {
                if (in_array($heure, $heures_modifiables)) {
                    // Generate the form with radio buttons for modifiable hours
                    echo "<td><form action='modifier_etat_presences.php' method='POST'>";
                    echo '<input type="hidden" name="id" value="' . $row['ID'] . '">';
                    echo '<input type="hidden" name="jour" value="' . $jour . '">';
                    echo '<input type="hidden" name="heure" value="' . $heure . '">';
                    echo '<input type="hidden" name="classePrecedente" value="' . $classe . '">';
                    echo '<input type="hidden" name="jourPrecedent" value="' . $jour . '">';
                    echo '<div>';
                    echo "<div class='presence-buttons'>";

                    foreach (array('present', 'retard', 'absent') as $e) {
                        $checked = (isset($presenceData[$row['ID']][$heure]) && $presenceData[$row['ID']][$heure] == $e) ? 'checked' : '';
                        echo "<label class='etat-btn'>";
                        echo '<input class="' . $e . '-btn" type="radio" name="etat" value="' . $e . '" ' . $checked . '>';
                        echo '<span class="presence"></span>';
                        echo "</label>";
                    }
                    echo "</div>";
                    echo "<input type='submit' class='submit-btn hidden'>";
                    echo '</div>';
                    echo "</form></td>";
                } else {
                        // Generate static radio buttons for non-modifiable hours
                        echo "<td><form><div class='presence-buttons'>";

                        foreach (array('present', 'retard', 'absent') as $e) {
                            $checked = (isset($presenceData[$row['ID']][$heure]) && $presenceData[$row['ID']][$heure] == $e) ? 'checked' : '';
                            //echo "$isChecked";
                            echo "<label class='etat-btn'>";
                            echo '<input class="' . $e . '-btn" type="radio" name="etat" value="' . $e . '" ' . $checked . ' disabled>';
                            echo '<span class="presence"></span>';
                            echo "</label>";
                    }
                    echo "</div></form></td>";
                }
            }
            echo "</tr>";
        }
        } else {
            while ($row = $studentResult->fetch_assoc()) {
                echo "<tr><td>" . $row['prenom'] . " " . $row['nom'] . "</td>";
    
                // Boucle sur chaque heure de cours
                for ($heure = 1; $heure <= 8; $heure++) {
                        // Generate the form with radio buttons for modifiable hours
                        echo "<td><form action='modifier_etat_presences.php' method='POST'>";
                        echo '<input type="hidden" name="id" value="' . $row['ID'] . '">';
                        echo '<input type="hidden" name="jour" value="' . $jour . '">';
                        echo '<input type="hidden" name="heure" value="' . $heure . '">';
                        echo '<input type="hidden" name="classePrecedente" value="' . $classe . '">';
                        echo '<input type="hidden" name="jourPrecedent" value="' . $jour . '">';
                        echo '<div>';
                        echo "<div class='presence-buttons'>";
    
                        foreach (array('present', 'retard', 'absent') as $e) {
                            $checked = (isset($presenceData[$row['ID']][$heure]) && $presenceData[$row['ID']][$heure] == $e) ? 'checked' : '';
                            echo "<label class='etat-btn'>";
                            echo '<input class="' . $e . '-btn" type="radio" name="etat" value="' . $e . '" ' . $checked . '>';
                            echo '<span class="presence"></span>';
                            echo "</label>";
                        }
                        echo "</div>";
                        echo "<input type='submit' class='submit-btn hidden'>";
                        echo '</div>';
                        echo "</form></td>";
                }
                echo "</tr>";
            }
        }

        // Fermer la connexion à la base de données
        $conn->close();

        echo '</table>';
    
    ?>



    <script>
        // JavaScript pour changer l'état de présence au clic
        document.addEventListener('DOMContentLoaded', function() {
            const presenceButtons = document.querySelectorAll('.presence');
            presenceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const isChecked = button.classList.contains('present') || button.classList.contains('retard') || button.classList.contains('absent');
                    if (isChecked) {
                        button.classList.remove('present', 'retard', 'absent');
                    } else {
                        button.classList.add('present');
                    }
                });
            });
        });
    </script>
    

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Ajout de l'interactivité pour les boutons de présence
        document.querySelectorAll('.presence').forEach(button => {
            button.addEventListener('click', function() {
                const radioButton = this.previousElementSibling;
                if (radioButton) {
                    radioButton.checked = true;
                    radioButton.dispatchEvent(new Event('change'));  // Trigger change event immediately
                }
            });
        });

        // Fonction pour sélectionner toutes les options "Présent" lors du clic sur "Tout le monde présent"
        function selectAll() {
            // Sélectionnez tous les boutons de présence autorisés
            var presentButtons = document.querySelectorAll('input[type="radio"][value="present"]:not(:disabled)');

            // Cochez chaque bouton de présence autorisé
            presentButtons.forEach(button => {
                button.checked = true;
                button.dispatchEvent(new Event('change'));  // Trigger change event immediately
                // Show the submit button
                var submitBtn = button.closest('form').querySelector('.submit-btn');
                if (submitBtn) {
                    submitBtn.classList.remove('hidden');
                }
            });
        }

        // Get all radio buttons
        var radioButtons = document.querySelectorAll('input[type="radio"]');

        // Loop through each radio button
        radioButtons.forEach(function(radio) {
            // Add an onChange event listener to each radio button
            radio.addEventListener('change', function() {
                // When a radio button is changed, find the associated submit button
                var submitBtn = this.closest('form').querySelector('.submit-btn');
                if (submitBtn) {
                    submitBtn.classList.remove('hidden');
                }
            });
        });
    });

    // Consider exposing selectAll to global scope if it's intended to be called from HTML directly
    window.selectAll = selectAll;
</script>

<div class="button-container">
    <a href="liste_presence.php?classe=<?php echo $classe ?>&jour=lundi" class="button">Lundi</a>
    <a href="liste_presence.php?classe=<?php echo $classe ?>&jour=mardi" class="button">Mardi</a>
    <a href="liste_presence.php?classe=<?php echo $classe ?>&jour=mercredi" class="button">Mercredi</a>
    <a href="liste_presence.php?classe=<?php echo $classe ?>&jour=jeudi" class="button">Jeudi</a>
    <a href="liste_presence.php?classe=<?php echo $classe ?>&jour=vendredi" class="button">Vendredi</a>
</div>

    <?php 
        if ($_SESSION['isAdmin'] == true) {
            echo "<a href='admin_page.php' class='button return-home'>Revenir à l'accueil</a>";
        } else {
            echo "<a href='user_page.php' class='button return-home'>Revenir à l'accueil</a>";
        }
    ?>

<div class="legend">
    <span class="present">Présent</span>
    <span class="late">Retard</span>
    <span class="absent">Absent</span>
</div>


</body>
</html>