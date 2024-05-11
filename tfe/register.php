<?php
@include 'config.php';

session_start();

$error = array(); // Déclaration d'un tableau pour stocker les erreurs

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']); 
    $surname = mysqli_real_escape_string($conn, $_POST['surname']); // Prénom ajouté
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user_type = $_POST['user_type'];
    $class = mysqli_real_escape_string($conn, $_POST['class']); // Classe ajoutée
    $subject = mysqli_real_escape_string($conn, $_POST['subject']); // Matière ajoutée

    // Vérifier si l'utilisateur existe déjà
    $select = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if(mysqli_num_rows($result) > 0){
        $error[] = 'Vous êtes déjà inscrit!';
    } else {
        // Vérifier si les mots de passe correspondent
        if($pass != $cpass){
            $error[] = 'Vos mots de passe ne correspondent pas!';
        } else {
            // Hacher le mot de passe
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $insert = "INSERT INTO user_form(name, surname, email, password, user_type, `classe`, matière) VALUES('$name','$surname','$email','$password_hash','$user_type','$class','$subject')";
            mysqli_query($conn, $insert);
            header('location:login.php');
            exit();
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
   <title>Demander un compte</title>
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

      .form-container form select option{
         background: #fff;
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
      <h3>Faites votre demande d'inscription</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Entrez votre nom">
      <input type="text" name="surname" required placeholder="Entrez votre prénom"> 
      <input type="email" name="email" required placeholder="Entrez votre email">
      <input type="password" name="password" required placeholder="Entrez votre mot de passe">
      <input type="password" name="cpassword" required placeholder="Confirmez votre mot de passe ">
      <select name="user_type" onchange="toggleFields(this.value)">
         <option value="" selected>Choisissez votre rôle</option>
         <option value="user">Professeur</option>
         <option value="admin">Administrateur</option>
      </select>
      <div id="classField" style="display:none;"> <!-- Champ de classe masqué par défaut -->
         <input type="text" name="class"  placeholder="Entrez de quelle classe vous êtes titulaire">
      </div>
      <!-- Champ pour la matière enseignée -->
      <div id="subjectField" style="display:none;"> <!-- Champ de matière masqué par défaut -->
         <input type="text" name="subject"  placeholder="Entrez la matière enseignée">
      </div>
      <input type="submit" name="submit" value="Inscrivez-vous" class="form-btn">
      <p>Vous avez déjà un compte? <a href="login.php">Connectez-vous</a></p>
   </form>
</div>

<script>
function toggleFields(userType) {
    var classField = document.getElementById("classField");
    var subjectField = document.getElementById("subjectField");
    if (userType === "user") {
        classField.style.display = "block";
        subjectField.style.display = "block"; // Afficher le champ de matière pour les professeurs
    } else {
        classField.style.display = "none";
        subjectField.style.display = "none"; // Masquer le champ de matière pour les administrateurs
    }
}
</script>

</body>
</html>
