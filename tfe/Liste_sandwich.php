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
$queryClasses = "SELECT DISTINCT classe FROM sandwiches ORDER BY classe";
$resultClasses = $conn->query($queryClasses);

// Vérifier si une classe a été sélectionnée ou si "Toutes les classes" a été sélectionné
$classe_choisie = isset($_GET['classe']) ? $_GET['classe'] : '';
$toutes_classes = ($classe_choisie && $classe_choisie === 'toutes') ? true : false;

// Récupérer les sandwichs en fonction de la sélection
$querySandwiches = $toutes_classes ? "SELECT id, classe, eleve, quantite, type, approuve, modif FROM sandwiches ORDER BY classe, eleve" : ($classe_choisie ? "SELECT id, classe, eleve, quantite, type, approuve, modif FROM sandwiches WHERE classe = '". $conn->real_escape_string($classe_choisie) ."' ORDER BY eleve" : '');
$resultSandwiches = ($classe_choisie || $toutes_classes) ? $conn->query($querySandwiches) : null;

// Préparer un tableau pour regrouper les sandwichs par classe
$sandwichsParClasse = [];

if ($resultSandwiches && $resultSandwiches->num_rows > 0) {
    while ($row = $resultSandwiches->fetch_assoc()) {
        if (isset($row["classe"])) { // Vérifier si la clé "classe" existe dans $row
            $sandwichsParClasse[$row["classe"]][] = $row;
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
    <title>Sandwichs</title>
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

        .sandwich-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sandwich-card h3 {
            color: #5cb85c;
            margin-top: 0;
        }

        .sandwich-card p {
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

        .button-group {
            display: inline-block;
        }

        .button-group form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sandwichs par Classe</h2>
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
            <input type="submit" value="Afficher les sandwichs">
        </form>
        <?php 
        if ($classe_choisie || $toutes_classes) {
            if ($sandwichsParClasse) {
                foreach ($sandwichsParClasse as $classe => $sandwichs) {
                    // Afficher le nom de la classe une seule fois
                    echo "<h3>$classe</h3>";
                    foreach ($sandwichs as $sandwich) {
                        // Utiliser une 'card' pour chaque sandwich
                        echo "<div class='sandwich-card'>";
                        echo "<p><strong>Élève:</strong> " . $sandwich["eleve"] . "</p>";
                        echo "<p><strong>Quantité:</strong> " . $sandwich["quantite"] . "</p>";
                        echo "<p><strong>Type de sandwich:</strong> " . $sandwich["type"] . "</p>";
                        echo "<p><strong>Modifications:</strong> " . $sandwich["modif"] . "</p>";
                        // Boutons Supprimer et Approuver
                        echo "<div class='button-group'>";
                        // Bouton Supprimer
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='id' value='" . $sandwich["id"] . "'>";
                        echo "<input type='hidden' name='action' value='supprimer'>";
                        echo "<input type='submit' value='Supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce sandwich ?\")'>";
                        echo "</form>";
                        // Bouton Approuver
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='id' value='" . $sandwich["id"] . "'>";
                        echo "<input type='hidden' name='action' value='approuver'>";
                        echo "<input type='submit' value='Approuver'>";
                        echo "</form>";
                        echo "</div>"; // Fin de button-group
                        echo "</div>"; // Fin de sandwich-card
                    }
                }
            } else {
                // Si aucun sandwich n'est trouvé, afficher un message
                echo "<p>Aucun sandwich trouvé.</p>";
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
    if(supprimerSandwich($conn, $id)) {
        // Sandwich supprimé avec succès
        echo "Sandwich supprimé avec succès.";
    } else {
        // Erreur lors de la suppression du sandwich
        echo "Erreur lors de la suppression du sandwich.";
    }
}

// Vérifier si une demande d'approbation a été reçue
if(isset($_POST['action']) && $_POST['action'] == 'approuver' && isset($_POST['id'])) {
    $id = $_POST['id'];
    if(approuverSandwich($conn, $id)) {
    } else {
        // Erreur lors de l'approbation du sandwich
        echo "Erreur lors de l'approbation du sandwich.";
    }
}

// Fonction pour supprimer un sandwich
function supprimerSandwich($conn, $id) {
    $sql = "DELETE FROM sandwiches WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Fonction pour approuver un sandwich
function approuverSandwich($conn, $id) {
    $sql = "UPDATE sandwiches SET approuve = '1' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>
