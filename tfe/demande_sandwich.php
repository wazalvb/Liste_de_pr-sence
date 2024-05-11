<?php 
    // Démarrer la session
    session_start();

    // Inclure le fichier de configuration
    @include 'config.php';

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de rapport</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        select, textarea, input[type="submit"], input[type="button"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"], input[type="button"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #0056b3;
        }

        /* Style pour le bouton "Revenir à l'accueil" */
        .bottom-button {
            bottom: 20px;
            margin-left: 46%;
        }

        .bottom-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10%;
        }

        .bottom-button a:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function updateClasses() {
            var eleveSelect = document.getElementById("eleve");
            var classeSelect = document.getElementById("classe");
            var selectedEleve = eleveSelect.options[eleveSelect.selectedIndex];
            var selectedClasse = selectedEleve.getAttribute("data-classe");

            // Réinitialiser les options de la liste déroulante des classes
            classeSelect.innerHTML = "";

            // Ajouter une option par défaut pour la classe
            var defaultOption = document.createElement("option");
            defaultOption.text = "Sélectionner la classe";
            defaultOption.value = "";
            classeSelect.add(defaultOption);

            // Ajouter l'option de classe correspondant à l'élève sélectionné
            var classeOption = document.createElement("option");
            classeOption.text = selectedClasse;
            classeOption.value = selectedClasse;
            classeSelect.add(classeOption);
        }

        function updateEleveList() {
            const classeSelect = document.getElementById('classe');
            const classeSelectionne = classeSelect.value;
            const eleveSelect = document.getElementById('eleve');

            // Vider les options pour les élèves existantes
            eleveSelect.innerHTML = '<option value="" disabled selected>Sélectionner l\'élève</option>';

            // Rediriger vers la même page avec la classe sélectionnée en paramètre
            window.location.href = 'demande_sandwich.php?classe=' + classeSelectionne;
        }

        function submitForm() {
            var form = document.getElementById("rapportForm");
            var eleveSelect = document.getElementById("eleve");
            var classeSelect = document.getElementById("classe");
            var eleveName = eleveSelect.options[eleveSelect.selectedIndex].text;
            var eleveValue = eleveSelect.options[eleveSelect.selectedIndex].value;
            var formData = new FormData(form);

            // Vérifier si une classe et un élève ont été sélectionnés
            if (eleveValue === "" || classeSelect.value === "") {
                alert("Veuillez sélectionner une classe et un élève.");
                return; // Arrêter la soumission du formulaire si une classe ou un élève n'a pas été sélectionné
            }

            // Modifier la valeur de l'élève dans le FormData pour inclure le nom et prénom
            formData.set("eleve", eleveName);

            // Envoyer les données du formulaire à votre script PHP de traitement
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Erreur lors de l\'envoi des données');
            })
            .then(data => {
                console.log(data); // Afficher la réponse du serveur dans la console
                alert("Rapport inséré avec succès !");
                // Effacer les informations du formulaire après soumission
                form.reset();
            })
            .catch(error => {
                console.error('Erreur :', error);
                alert("Une erreur est survenue lors de l'insertion du rapport.");
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Demande de sandwich</h2>
        <!-- Formulaire pour créer un rapport -->
        <form id="rapportForm" method="post">

            <label for="classe">Classe :</label>
            <!-- Liste déroulante pour la classe -->
            <select id="classe" name="classe" onchange="updateEleveList()">
                <option value="" disabled selected>Sélectionner la classe</option>
                <?php 
                    $query = "SELECT DISTINCT classe FROM student";
                    $res = $conn->query($query);
                
                    if ($res->num_rows > 0) {
                        while($row = $res->fetch_assoc()) {
                            if($_GET['classe'] == $row['classe']) {
                                echo "<option value='{$row['classe']}' selected>{$row['classe']}</option>";
                            }
                            else {
                                echo "<option value='{$row['classe']}'>{$row['classe']}</option>";
                            }
                        }
                    } else {
                        echo "<option value='' disabled>Pas de classes enregistrées</option>";
                    }
                ?>
            </select>

            <label for="eleve">Nom de l'élève :</label>
            <!-- Liste déroulante pour le nom de l'élève -->
            <select id="eleve" name="eleve">
                <option value="" disabled selected>Sélectionner l'élève</option>
                <?php
                    if (isset($_GET['classe'])) {
                        $classeSelectionne = $_GET['classe'];

                        // Préparer la requête
                        $stmt = $conn->prepare("SELECT id, prenom, nom, classe FROM student WHERE classe = ?");
                        $stmt->bind_param("s", $classeSelectionne);
                        $stmt->execute();
                        $result_eleves = $stmt->get_result();

                        if ($result_eleves->num_rows > 0) {
                            while ($row = $result_eleves->fetch_assoc()) {
                                $prenom = $row["prenom"];
                                $nom = $row["nom"];
                                $classe = $row["classe"];
                                $eleve_nom_complet = "$prenom $nom";
                                echo "<option value='$eleve_nom_complet' data-classe='$classe'>$eleve_nom_complet</option>";
                            }
                        } else {
                            echo "<option value='' disabled>Aucun élève trouvé</option>";
                        }

                        $stmt->close();
                    }
                ?>
            </select>
            
            <label for="quantite">Nombre de sandwichs souhaités :</label>
            <input type="number" id="quantite" name="quantite" min="1" value="1">

            <!-- Ajout de la sélection du type de rapport -->
            <label for="type">Quel sandwich voulez-vous ? </label>
            <select id="type" name="type">
                <option value="usa">USA</option>
                <option value="thonmayo">Thon Mayonnaise</option>
                <option value="jambon beurre">Jambon beurre</option>
                <option value="club">Club</option>
            </select>

            <label for="modif">Des modifications ? </label>
            <!-- Champ pour le rapport -->
            <textarea id="modif" name="modif" rows="4" cols="50"></textarea>

            <!-- Bouton pour soumettre le formulaire -->
            <input type="button" value="envoyez la demande de sandwich" onclick="submitForm()">
        </form>
    </div>

    <?php
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $eleve = $_POST["eleve"];
        $classe = $_POST["classe"];
        $type = $_POST["type"];
        $modif = $_POST["modif"];
        $quantite = $_POST["quantite"];

        // Vérifier la connexion à la base de données
        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué: " . $conn->connect_error);
        }

        // Requête préparée pour insérer les données dans la base de données
        $stmt = $conn->prepare("INSERT INTO sandwiches (eleve, classe, type, modif, quantite) VALUES (?, ?, ?, ?, ?)");

        // Vérifier si la requête préparée est correctement préparée
        if ($stmt === false) {
            die("Erreur de préparation de la requête: " . $conn->error);
        }

        // Binder les paramètres à la requête
        $stmt->bind_param("ssssi", $eleve, $classe, $type, $modif, $quantite);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "<script>alert('Rapport inséré avec succès.');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'insertion du rapport: " . $conn->error . "');</script>";
        }

        // Fermer la requête
        $stmt->close();
    }
    ?>


    <!-- Bouton "Revenir à l'accueil" -->
    <div class="bottom-button">
        <a href="user_page.php">Revenir à l'accueil</a>
    </div>
</body>
</html>
