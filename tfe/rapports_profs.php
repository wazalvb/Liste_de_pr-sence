<?php 
// Démarrer la session
session_start();

// Inclure le fichier de configuration
@include 'config.php';

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les classes de la base de données
$queryClasses = "SELECT DISTINCT classe FROM rapport ORDER BY classe";
$resultClasses = $conn->query($queryClasses);

// Vérifier si une classe a été sélectionnée ou si "Toutes les classes" a été sélectionné
$classe_choisie = isset($_GET['classe']) ? $_GET['classe'] : '';
$toutes_classes = ($classe_choisie && $classe_choisie === 'toutes') ? true : false;

// Récupérer les rapports en fonction de la sélection
$queryRapports = $toutes_classes ? "SELECT id, classe, eleve, rapport, type FROM rapport ORDER BY classe, eleve" : ($classe_choisie ? "SELECT id, classe, eleve, rapport, type FROM rapport WHERE classe = '". $conn->real_escape_string($classe_choisie) ."' ORDER BY eleve" : '');
$resultRapports = ($classe_choisie || $toutes_classes) ? $conn->query($queryRapports) : null;

// Préparer un tableau pour regrouper les rapports par classe
$rapportsParClasse = [];

if ($resultRapports && $resultRapports->num_rows > 0) {
    while ($row = $resultRapports->fetch_assoc()) {
        if (isset($row["classe"])) { // Vérifier si la clé "classe" existe dans $row
            $rapportsParClasse[$row["classe"]][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports</title>
    <!-- Styles CSS -->
    <style>
        /* Styles CSS ici */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 40px;
            text-align: center;
        }

        select, input[type="submit"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .rapport-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .rapport-card h3 {
            color: #5cb85c;
            margin-top: 0;
        }

        .rapport-card p {
            margin: 5px 0;
            line-height: 1.6;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rapports par Classe</h2>
        <form action="" method="get">
            <label for="classe">Choisissez une classe :</label>
            <select name="classe" id="classe">
                <option value="">Sélectionner une classe</option>
                <option value="toutes" <?php echo $toutes_classes ? 'selected' : ''; ?>>Toutes les classes</option>
                <?php
                // Afficher les options de classe
                if ($resultClasses->num_rows > 0) {
                    while ($row = $resultClasses->fetch_assoc()) {
                        $classe = $row["classe"];
                        $selected = ($classe === $classe_choisie) ? 'selected' : '';
                        echo "<option value='$classe' $selected>$classe</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" value="Afficher les rapports">
        </form>
        <?php 
        if ($classe_choisie || $toutes_classes) {
            if ($rapportsParClasse) {
                foreach ($rapportsParClasse as $classe => $rapports) {
                    // Afficher le nom de la classe une seule fois
                    echo "<h3>$classe</h3>";
                    foreach ($rapports as $rapport) {
                        // Utiliser une 'card' pour chaque rapport
                        echo "<div class='rapport-card'>";
                        echo "<p><strong>Élève:</strong> " . $rapport["eleve"] . "</p>";
                        echo "<p><strong>Type de rapport:</strong> " . $rapport["type"] . "</p>"; // Affichage du type de rapport
                        echo "<p><strong>Rapport:</strong> " . $rapport["rapport"] . "</p>";
                        // Ajouter le bouton Supprimer avec un formulaire caché pour chaque rapport
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='id' value='" . $rapport["id"] . "'>";
                        echo "<input type='hidden' name='action' value='supprimer'>";
                        echo "<input type='submit' value='Supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce rapport ?\")'>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
            } else {
                // Si aucun rapport n'est trouvé, afficher un message
                echo "<p>Aucun rapport trouvé.</p>";
            }
        } else {
            // Si aucune classe n'est sélectionnée, afficher un message
            echo "<p>Veuillez sélectionner une classe.</p>";
        }
        ?>
        <!-- Bouton pour revenir à l'accueil -->
        <div class="button-container">
            <a href="admin_page.php" class="back-button">Revenir à l'accueil</a>
        </div>
    </div>
</body>
</html>

<?php
// Vérifier si une demande de suppression a été reçue
if(isset($_POST['action']) && $_POST['action'] == 'supprimer' && isset($_POST['id'])) {
    $id = $_POST['id'];
    if(supprimerRapport($conn, $id)) {
        // Rapport supprimé avec succès
        echo "Rapport supprimé avec succès.";
    } else {
        // Erreur lors de la suppression du rapport
        echo "Erreur lors de la suppression du rapport.";
    }
}

// Fonction pour supprimer un rapport
function supprimerRapport($conn, $id) {
    $sql = "DELETE FROM rapport WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>
