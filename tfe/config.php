<?php

$conn = mysqli_connect('localhost','root','','user_db');

if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

?>


