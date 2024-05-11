<?php
@include 'config.php';

session_start();

$error = array(); // Déclaration d'un tableau pour stocker les erreurs

if(isset($_POST['submit'])) {
   // Vérification des champs requis
   if(empty($_POST['email'])) {
      $error[] = "Email is required";
   } else {
      $email = mysqli_real_escape_string($conn, $_POST['email']);
   }

   if(empty($_POST['password'])) {
      $error[] = "Password is required";
   } else {
      $pass = $_POST['password']; // Ne pas hasher le mot de passe ici
   }

   // Si aucun des champs requis n'est vide, procéder à l'authentification
   if(empty($error)) {
      $select = "SELECT * FROM user_form WHERE email = '$email'";
      $result = mysqli_query($conn, $select);

      if(mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_array($result);

         // Vérification du mot de passe
         if (password_verify($pass, $row['password'])) {
            if($row['certification'] == '1') {
               if($row['user_type'] == 'admin') {
                  $_SESSION['user_surname'] = $row['surname'];
                  $_SESSION['user_id'] = $row['id'];
                  $_SESSION['isAdmin'] = true;
                  header('location:admin_page.php');
                  exit(); // Arrêter l'exécution du script après la redirection
               } else if($row['user_type'] == 'user') {
                  $_SESSION['user_surname'] = $row['surname'];
                  $_SESSION['user_id'] = $row['id'];
                  $_SESSION['isAdmin'] = false;
                  header('location:user_page.php');
                  exit(); // Arrêter l'exécution du script après la redirection
               }
            } else {
               $error[] = 'Attente de confirmation du compte ';
            }
         } else {
            $error[] = 'Email ou mot de passe incorrect !';
         }
      } else {
         $error[] = 'Email ou mot de passe incorrect !';
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
   <title>Page de connexion</title>
   <style>
      <?php echo "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');"; ?>

      *{
         font-family: 'Poppins', sans-serif;
         margin:0; padding:0;
         box-sizing: border-box;
         outline: none; border:none;
         text-decoration: none;
      }

      .form-container{
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding:20px;
         padding-bottom: 60px;
         background: #eee;
      }

      .form-container form{
         padding:20px;
         border-radius: 5px;
         box-shadow: 0 5px 10px rgba(0,0,0,.1);
         background: #fff;
         text-align: center;
         width: 500px;
      }

      .form-container form h3{
         font-size: 30px;
         text-transform: uppercase;
         margin-bottom: 10px;
         color:#333;
      }

      .form-container form input,
      .form-container form select{
         width: 100%;
         padding:10px 15px;
         font-size: 17px;
         margin:8px 0;
         background: #eee;
         border-radius: 5px;
      }

      .form-container form .form-btn{
         background: #fbd0d9;
         color:crimson;
         text-transform: capitalize;
         font-size: 20px;
         cursor: pointer;
      }

      .form-container form .form-btn:hover{
         background: crimson;
         color:#fff;
      }

      .form-container form p{
         margin-top: 10px;
         font-size: 20px;
         color:#333;
      }

      .form-container form p a{
         color:crimson;
      }

      .form-container form .error-msg{
         margin:10px 0;
         display: block;
         background: crimson;
         color:#fff;
         border-radius: 5px;
         font-size: 20px;
         padding:10px;
      }
   </style>
</head>
<body>
   
<div class="form-container">
   <form action="" method="post">
      <h3>Connectez-vous maintenant</h3>
      <?php
      // Afficher les erreurs s'il y en a
      if(!empty($error)) {
         foreach($error as $err) {
            echo '<span class="error-msg">'.$err.'</span>';
         }
      }
      ?>
      <input type="email" name="email" required placeholder="Mettez votre email ">
      <input type="password" name="password" required placeholder="Mettez votre mot de passe">
      <input type="submit" name="submit" value="Connexion" class="form-btn">
      <p>Vous êtes nouveaux prof ? Inscrivez-vous <a href="register.php">  &ensp;&ensp;&ensp;&ensp;Je fais ma demande</a></p>
   </form>
</div>

</body>
</html>
