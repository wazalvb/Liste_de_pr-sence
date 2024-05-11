<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de classe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        .class-link {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none; /* Supprimer le soulignement du lien */
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .class-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choisissez votre classe :</h1>
        <?php
        @include 'config.php';

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Requête pour récupérer les classes
        $sql = "SELECT DISTINCT classe FROM student";
        $result = $conn->query($sql);

        // Afficher les classes comme des liens
        if ($result->num_rows > 0) {
            // Afficher chaque classe comme un lien
            while($row = $result->fetch_assoc()) {
                echo "<a class='class-link' href='liste_presence.php?classe={$row["classe"]}'>{$row['classe']}</a> <br>";
            }
        } else {
            echo "0 résultats";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
