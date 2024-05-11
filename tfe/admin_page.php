<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_surname'])){
   header('location:login.php');
}

if(isset($_POST['logout'])){
   header('location:logout.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Page Administrateur</title>
   <style>
      body {
         margin: 0;
         padding: 0;
         font-family: Arial, sans-serif;
         background-color: #f4f4f4;
      }

      .container {
         max-width: 800px;
         margin: 50px auto;
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         transition: opacity 0.5s;
         opacity: 0;
      }

      .top {
         background-color: #007bff;
         color: #fff;
         text-align: center;
         padding: 20px;
         border-top-left-radius: 10px;
         border-top-right-radius: 10px;
      }

      .top h3 {
         margin: 0;
      }

      .options {
         padding: 20px;
      }

      .options h2 {
         margin-top: 0;
      }

      .options ul {
         list-style: none;
         padding: 0;
      }

      .options li {
         margin-bottom: 10px;
      }

      .options a {
         display: inline-block;
         padding: 10px 20px;
         background-color: #007bff;
         color: #fff;
         text-decoration: none;
         border-radius: 5px;
         transition: background-color 0.3s;
      }

      .options a:hover {
         background-color: #0056b3;
      }
      .options form {
         text-align: center;
         margin-top: 20px;
      }

      .options form input[type="submit"] {
         padding: 10px 20px;
         background-color: #dc3545;
         color: #fff;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
      }

.options form input[type="submit"]:hover {
   background-color: #c82333;
}
   </style>
</head>

<body>
   <div class="container">
      <div class="top">
         <h3>Admin</h3>
         <h1>Bonjour <?php echo $_SESSION['user_surname'] ?></h1>
      </div>

      <div class="options">
         <h2>Que voulez-vous faire :</h2>
         <ul>
            <li><a href="choix_classe_presence.php">Voir les listes de présences</a></li>
            <li><a href="rapports_profs.php">Voir les rapports des profs</a></li>
            <li><a href="./choix horraire/choix_classe_horraire.php">Voir les horaires des élèves</a></li>
            <li><a href="liste_sandwich.php">Voir la liste des sandwichs</a></li>
         </ul>
         <form method="post">
            <input type="submit" name="logout" value="Se déconnecter">
         </form>
      </div>
   </div>

   <script>
      // Ajouter un effet de transition lorsque la page est chargée
      document.addEventListener('DOMContentLoaded', function() {
         document.querySelector('.container').style.opacity = 1;
      });
   </script>
</body>
</html>
