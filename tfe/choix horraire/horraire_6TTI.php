<?php
session_start();

@include '../config.php';

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des élèves de la classe "6TTI"
$sql_students = "SELECT prenom, nom FROM student WHERE classe = '6TTI'";
$result_students = $conn->query($sql_students);
$students = [];

if ($result_students->num_rows > 0) {
    while ($row = $result_students->fetch_assoc()) {
        $students[] = $row["prenom"] . " " . $row["nom"];
    }
}

// Requête SQL pour récupérer les professeurs de la classe "6TTI"
$sql = "SELECT name, matière, surname FROM user_form WHERE classe = '6TTI'";
$result = $conn->query($sql);
$teachers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = "<li class='teacher-item'><span class='teacher-name'>" . $row["name"] . " " . $row["surname"] . "</span> - <span class='teacher-subject'>" . $row["matière"] . "</span></li>";
    }
} else {
    $teachers[] = "<li>Il n'y a aucun titulaire pour cette classe.</li>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horaires des classes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .name-listr{
            color:#3D5287 ;
            margin-top: 50%;
        }

        .name-listl{
            color: #3D5287 ;
            margin-top: 45%;
        }

        .student-list {
            list-style-type: none;
            padding: 0;
            max-width: 300px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }

        .student-list li {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .table {
            flex-grow: 1;
            margin-left: 20px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-left: 5%;
            margin-right: 5%;
        }

        .table h1 {
            color: #0056b3;
        }

        .table h2 {
            margin-top: 50px;
        }

        th, td {
            padding: 5px;
            border: 2px solid #ddd; /* Définir une bordure de 2px pour toutes les cellules */
            vertical-align: middle;
            height: 30px; /* Définir une hauteur de 30px pour toutes les cellules */
            width: 120px; /* Définir une largeur de 120px pour toutes les cellules */
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            background-color: #ffffff;
        }

        .same-course {
            background-color: #f2f2f2;
            border-top-color: transparent;
            border-bottom-color: transparent;
            border-left-color: transparent; 
        }

        .same-course:first-child {
            border-left: 2px solid #ddd; /* Définir une bordure de 2px pour la première cellule */
        }

        .same-course:last-child {
            border-right: 2px solid #ddd; /* Définir une bordure de 2px pour la dernière cellule */
        }

        .local {
            font-size: 10px;
            color: #666;
        }

        .local-num {
            font-weight: bold;
        }

        .merged {
            display: none;
        }


        /* Couleurs de fond pour chaque cours */
        td.histoire { background-color: #FFE0B2; }
        td.laboinfo { background-color: #B3E5FC; }
        td.infos { background-color: #C8E6C9; }
        td.cpc { background-color: #FFCCBC; }
        td.maths { background-color: #FFECB3; }
        td.francais { background-color: #D1C4E9; }
        td.langmod1 { background-color: #C5CAE9; }
        td.physique { background-color: #B2DFDB; }
        td.TPT { background-color: #FFAB91; }
        td.labol { background-color: #81C784; }
        td.bio { background-color: #FFD54F; }
        td.religion { background-color: #EF9A9A; }
        td.chimie { background-color: #90CAF9; }
        td.geographie { background-color: #BCAAA4; }
        td.edupe { background-color: #BDBDBD; }
        td.moral { background-color: #E0E0E0; }
        td.hist { background-color: #FFCDD2; }

        .teacher-list {
            list-style-type: none;
            padding: 0;
            max-width: 300px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }

        .teacher-list li {
            margin-bottom: 5px;
        }

        .teacher-name {
            font-weight: bold;
        }

        .teacher-subject {
            font-size: 12px;
            color: #666;
        }

        .button {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px; /* Espace entre les boutons */
    text-decoration: none;
    color: #ffffff;
    background-color: #007bff; /* Couleur de fond par défaut */
    border: 1px solid #007bff; /* Bordure par défaut */
    border-radius: 5px; /* Coins arrondis */
    transition: background-color 0.3s, color 0.3s; /* Transition fluide lors du survol */
    }

    .button.exit {
        background-color: #dc3545; /* Couleur de fond rouge */
        border-color: #c82333; /* Bordure au survol */
    }

    .button.exit:hover {
        background-color: #c82333;
        border-color: #c82333; /* Bordure au survol */
    }

    .button:hover {
        background-color: #0056b3; /* Couleur de fond au survol */
        border-color: #0056b3; /* Bordure au survol */
    }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <!-- Section de la liste des élèves -->
    <div>
        <h2 class="name-listl">Liste des élèves de 6TTI</h2>
        <ul class="student-list">
    <?php foreach ($students as $student) { ?>
        <li><?php echo $student; ?></li>
    <?php } ?>
</ul>

    </div>

    <!-- Section de l'horaire des classes -->
    <div class="table">
        <h1>Horaires des classes</h1>
        <h2>6TTI</h2>
        <!-- Tableau de l'horaire -->
        <table border="1">
            <tr>
                <th>Lundi</th>
                <th>Mardi</th>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
            </tr>
            <tr>
                <td class="histoire">histoire<br><span class="local local-num">local 1</span></td>
                <td rowspan="2" class="laboinfo">labo informatique<br><span class="local local-num">local 2</span></td>
                <td class="same-course"></td>
                <td rowspan="2" class="infos">informatique<br><span class="local local-num">local 3</span></td>
                <td class="cpc">cpc<br><span class="local local-num">local 4</span></td>
            </tr>
            <tr>
                <td class="maths">mathématique<br><span class="local local-num">local 5</span></td>
                <td class="same-course"></td>
                <td class="francais">français<br><span class="local local-num">local 6</span></td>
            </tr>
            <tr>
                <td class="francais">français<br><span class="local local-num">local 7</span></td>
                <td class="langmod1">langue moderne 1<br><span class="local local-num">local 8</span></td>
                <td class="langmod1">langue moderne 1 <br><span class="local local-num">local 9</span></td>
                <td class="francais">français<br><span class="local local-num">local 10</span></td>
                <td class="physique">physique<br><span class="local local-num">local 11</span></td>
            </tr>
            <tr>
                <td class="TPT">TPT<br><span class="local local-num">local 12</span></td>
                <td rowspan="2" class="labol">labo logique<br><span class="local local-num">local 13</span></td>
                <td class="maths">mathématique<br><span class="local local-num">local 14</span></td>
                <td class="bio">biologie<br><span class="local local-num">local 15</span></td>
                <td class="francais">français<br><span class="local local-num">local 16</span></td>
            </tr>
            <tr>
                <td class="same-course"></td>
                <td class="religion">religion islamique<br><span class="local local-num">local 17</span></td>
                <td class="chimie">chimie<br><span class="local local-num">local 18</span></td>
                <td class="geographie">géographie<br><span class="local local-num">local 19</span></td>
            </tr>
            <tr>
                <td rowspan="2" class="edupe">éducation physique<br><span class="local local-num">local 20</span></td>
                <td class="same-course"></td>
                <td class="same-course"></td>
                <td class="same-course"></td>
                <td class="same-course"></td>
            </tr>
            <tr>
                <td rowspan="2" class="maths">mathématique<br><span class="local local-num">local 21</span></td>
                <td class="same-course"></td>
                <td class="langmod1">langue moderne 1<br><span class="local local-num">local 22</span></td>
                <td class="moral">moral/cpc<br><span class="local local-num">local 23</span></td>
            </tr>
            <tr>
                <td class="maths">mathématique<br><span class="local local-num">local 24</span></td>
                <td class="same-course"></td>
                <td class="francais">français<br><span class="local local-num">local 25</span></td>
                <td class="hist">histoire<br><span class="local local-num">local 26</span></td>
            </tr>
            <tr>
                <td class="langmod1">langue moderne 1<br><span class="local local-num">local 27</span></td>
                <td class="geographie">géographie<br><span class="local local-num">local 28</span></td>
                <td class="same-course"></td>
                <td class="maths">mathématique<br><span class="local local-num">local 29</span></td>
                <td class="same-course"></td>
            </tr>
        </table>
       
           
        <?php 
            if ($_SESSION['isAdmin'] == true) {
                echo "<a href='../admin_page.php' class='button exit'>Revenir à l'accueil</a>";
            } else {
                echo "<a href='../user_page.php' class='button exit'>Revenir à l'accueil</a>";
            }
        ?>
        <a href="choix_classe_horraire.php" class="button">Revenir au choix de classe</a>
    </div>

   

    <!-- Section de la liste des professeurs -->
    <div>
        <h2 class="name-listr">Le titulaire de la classe</h2>
        <ul class="teacher-list">
            <?php foreach ($teachers as $teacher) { echo $teacher; } ?>
        </ul>
    </div>

    </script>
</body>
</html>
